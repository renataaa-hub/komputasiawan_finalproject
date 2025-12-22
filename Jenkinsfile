pipeline {
    agent any

    parameters {
        choice(name: 'DEPLOY_MODE', choices: ['azuredb', 'compose'], description: 'azuredb=1 container app (DB Azure). compose=app+mysql via docker compose')
    }

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        IMAGE_TAG        = "${BUILD_NUMBER}"     // tag unik tiap build
        CONTAINER_NAME   = 'kompuawan_app'
        DOCKER_NETWORK   = 'komputasiawan_finalproject_default'
    }

    stages {
        stage('Checkout SCM') {
            steps { checkout scm }
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

        /* =========================
           DEPLOY MODE: azuredb
           ========================= */
        stage('Deploy (azuredb) - Pull & Run App') {
            when { expression { params.DEPLOY_MODE == 'azuredb' } }
            steps {
                // ambil env file dari Jenkins Secret File
                withCredentials([file(credentialsId: 'env-azure', variable: 'ENV_AZURE_FILE')]) {
                    bat """
                    copy /Y "%ENV_AZURE_FILE%" .env.azure

                    docker pull %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest

                    REM stop/remove container lama (abaikan error)
                    docker stop %CONTAINER_NAME% 2>nul
                    docker rm %CONTAINER_NAME% 2>nul

                    REM jalankan container baru (Apache port 80)
                    docker run -d --name %CONTAINER_NAME% -p 80:80 --env-file .env.azure %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                    """
                }
            }
        }

        /* =========================
           DEPLOY MODE: compose
           ========================= */
        stage('Deploy (compose) - App + MySQL') {
            when { expression { params.DEPLOY_MODE == 'compose' } }
            steps {
                withCredentials([
                    file(credentialsId: 'env-docker', variable: 'ENV_DOCKER_FILE')
                ]) {
                    bat """
                    copy /Y "%ENV_DOCKER_FILE%" .env.docker

                    docker pull %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest

                    REM Pastikan network ada (kalau compose kamu pakai network default project, boleh skip)
                    docker network create %DOCKER_NETWORK% 2>nul

                    REM stop stack lama kalau ada
                    docker compose -f docker-compose.prod.yml down 2>nul

                    REM jalankan stack baru
                    docker compose -f docker-compose.prod.yml up -d --build

                    REM optional: pastikan app join network yg sama (kalau perlu)
                    docker network connect %DOCKER_NETWORK% %CONTAINER_NAME% 2>nul
                    """
                }
            }
        }

        stage('Post-Deploy Verification') {
            steps {
                bat """
                docker ps
                docker logs --tail 50 %CONTAINER_NAME%
                """
            }
        }
    }

    post {
        always {
            echo "DONE. DEPLOY_MODE=${params.DEPLOY_MODE}, TAG=${env.IMAGE_TAG}"
        }
        success {
            echo 'Pipeline SUCCESS'
        }
        failure {
            echo 'Pipeline FAILED: Check Jenkins console logs'
        }
    }
}
