pipeline {
    agent any

    environment {
        // ==== ACR ====
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        IMAGE_TAG        = ''   // diisi otomatis

        // ==== Azure target (GANTI INI) ====
        RG_NAME      = 'GANTI_DENGAN_RESOURCE_GROUP_KAMU'
        WEBAPP_NAME  = 'GANTI_DENGAN_WEBAPP_NAME_KAMU'
    }

    stages {

        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Set Image Tag') {
            steps {
                script {
                    def commit = bat(script: "git rev-parse --short HEAD", returnStdout: true).trim()
                    env.IMAGE_TAG = "${env.BUILD_NUMBER}-${commit}"
                    echo "Using IMAGE_TAG: ${env.IMAGE_TAG}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                bat """
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
                withCredentials([
                    string(credentialsId: 'AZURE_CLIENT_ID', variable: 'AZ_CLIENT_ID'),
                    string(credentialsId: 'AZURE_CLIENT_SECRET', variable: 'AZ_CLIENT_SECRET'),
                    string(credentialsId: 'AZURE_TENANT_ID', variable: 'AZ_TENANT'),
                    string(credentialsId: 'AZURE_SUBSCRIPTION_ID', variable: 'AZ_SUB')
                ]) {
                    bat """
                    az login --service-principal -u %AZ_CLIENT_ID% -p %AZ_CLIENT_SECRET% --tenant %AZ_TENANT%
                    az account set --subscription %AZ_SUB%

                    REM Set image ke TAG unik (bukan latest)
                    az webapp config container set ^
                      --name %WEBAPP_NAME% ^
                      --resource-group %RG_NAME% ^
                      --docker-custom-image-name %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG% ^
                      --docker-registry-server-url https://%ACR_LOGIN_SERVER%

                    REM Restart supaya WebApp pull image baru
                    az webapp restart --name %WEBAPP_NAME% --resource-group %RG_NAME%
                    """
                }
            }
        }
    }

    post {
        success {
            echo "SUCCESS: pushed %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG% and deployed to WebApp %WEBAPP_NAME%"
        }
        failure {
            echo "FAILED: Check Jenkins console output."
        }
        always {
            // Opsional: logout biar bersih
            bat "az logout"
        }
    }
}
