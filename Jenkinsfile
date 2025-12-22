pipeline {
    agent any

    options {
        timestamps()
        timeout(time: 30, unit: 'MINUTES')
    }

    parameters {
        booleanParam(name: 'DO_DEPLOY', defaultValue: true, description: 'Deploy container setelah push ke ACR')
        string(name: 'ENV_FILE_PATH', defaultValue: 'C:\\deploy\\penaawan\\.env.azure', description: 'Path file .env.azure di mesin Jenkins/VM')
        string(name: 'HOST_PORT', defaultValue: '80', description: 'Port host untuk publish container')
    }

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        CONTAINER_NAME   = 'kompuawan_app'
        IMAGE_TAG        = "${BUILD_NUMBER}"
    }

    stages {

        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                bat """
                docker build -t %IMAGE_NAME%:%IMAGE_TAG% .
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %IMAGE_NAME%:latest
                """
            }
        }

        stage('Login to ACR') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'acr-credentials-1',
                    usernameVariable: 'ACR_USER',
                    passwordVariable: 'ACR_PASS'
                )]) {
                    bat """
                    echo %ACR_PASS% | docker login %ACR_LOGIN_SERVER% --username %ACR_USER% --password-stdin
                    """
                }
            }
        }

        stage('Tag Image for ACR') {
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

        stage('Deploy - Pull & Run Container') {
            when { expression { return params.DO_DEPLOY } }
            steps {
                bat """
                IF NOT EXIST "%ENV_FILE_PATH%" (
                  echo ERROR: Env file tidak ditemukan di "%ENV_FILE_PATH%"
                  exit /b 1
                )

                docker pull %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest

                REM stop/remove container lama (abaikan kalau tidak ada)
                docker stop %CONTAINER_NAME% 2>nul
                docker rm %CONTAINER_NAME% 2>nul

                REM run container baru (Apache port 80)
                docker run -d --restart unless-stopped --name %CONTAINER_NAME% ^
                  -p %HOST_PORT%:80 ^
                  --env-file "%ENV_FILE_PATH%" ^
                  %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                """
            }
        }

        stage('Post-Deploy Verification') {
            when { expression { return params.DO_DEPLOY } }
            steps {
                bat """
                docker ps
                docker logs --tail 60 %CONTAINER_NAME%
                """
            }
        }
    }

    post {
        success {
            echo "SUCCESS: Build+Push (dan Deploy jika DO_DEPLOY=true). TAG=${env.IMAGE_TAG}"
        }
        failure {
            echo "FAILED: Cek Console Output"
        }
    }
}
