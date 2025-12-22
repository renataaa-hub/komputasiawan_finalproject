pipeline {
    agent any

    environment {
        // ==== ACR ====
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        IMAGE_TAG        = 'dev'   // default fallback (tidak boleh kosong)

        // ==== Azure target (GANTI NANTI) ====
        RG_NAME     = 'Rg-PenaAwan'
        WEBAPP_NAME = 'Web-PenaAwan'
    }

    stages {

        stage('Checkout SCM') {
            steps { checkout scm }
        }

        stage('Set Image Tag (FIX)') {
            steps {
                script {
                    def commitShort = ''

                    // 1) Coba dari env Jenkins (paling aman)
                    if (env.GIT_COMMIT && env.GIT_COMMIT.trim()) {
                        commitShort = env.GIT_COMMIT.trim().take(7)
                    } else {
                        // 2) Fallback: tulis output git ke file lalu baca
                        bat '@git rev-parse --short HEAD > .gitshort'
                        commitShort = readFile('.gitshort').trim()
                    }

                    if (!commitShort) {
                        commitShort = "nogit"
                    }

                    env.IMAGE_TAG = "${env.BUILD_NUMBER}-${commitShort}"
                    echo "Using IMAGE_TAG: ${env.IMAGE_TAG}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                bat """
                echo Building Docker image...
                docker build -t %IMAGE_NAME%:%IMAGE_TAG% .
                """
            }
        }

        stage('Login to Azure Container Registry') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'acr-credentials-1',
                    usernameVariable: 'ACR_USER',
                    passwordVariable: 'ACR_PASS'
                )]) {
                    bat """
                    echo %ACR_PASS% | docker login %ACR_LOGIN_SERVER% ^
                      --username %ACR_USER% ^
                      --password-stdin
                    """
                }
            }
        }

        stage('Tag Docker Image') {
            steps {
                bat """
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                """
            }
        }

        stage('Push Image to ACR') {
            steps {
                bat """
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                """
            }
        }

        stage('Deploy to Azure WebApp (Set Image + Restart)') {
            steps {
                script {
                    // skip deploy kalau az belum ada
                    def azExists = bat(script: '@where az >nul 2>nul & echo %ERRORLEVEL%', returnStdout: true).trim()
                    if (azExists != '0') {
                        echo "Azure CLI (az) belum terinstall. Deploy DISKIP (Push ACR tetap jalan)."
                        return
                    }
                }

                withCredentials([
                    string(credentialsId: 'AZURE_CLIENT_ID', variable: 'AZ_CLIENT_ID'),
                    string(credentialsId: 'AZURE_CLIENT_SECRET', variable: 'AZ_CLIENT_SECRET'),
                    string(credentialsId: 'AZURE_TENANT_ID', variable: 'AZ_TENANT'),
                    string(credentialsId: 'AZURE_SUBSCRIPTION_ID', variable: 'AZ_SUB')
                ]) {
                    bat """
                    az login --service-principal -u %AZ_CLIENT_ID% -p %AZ_CLIENT_SECRET% --tenant %AZ_TENANT%
                    az account set --subscription %AZ_SUB%

                    az webapp config container set ^
                      --name %WEBAPP_NAME% ^
                      --resource-group %RG_NAME% ^
                      --docker-custom-image-name %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG% ^
                      --docker-registry-server-url https://%ACR_LOGIN_SERVER%

                    az webapp restart --name %WEBAPP_NAME% --resource-group %RG_NAME%
                    """
                }
            }
        }
    }

    post {
        success {
            echo "SUCCESS: %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%"
        }
        failure {
            echo "FAILED: Check Jenkins console output."
        }
        always {
            // POST jangan sampai bikin job gagal
            script {
                def azExists = bat(script: '@where az >nul 2>nul & echo %ERRORLEVEL%', returnStdout: true).trim()
                if (azExists == '0') {
                    bat '@az logout'
                } else {
                    echo "az not installed, skip logout"
                }
            }
        }
    }
}
