pipeline {
    agent any

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME = 'penaawan-app'
        IMAGE_TAG = 'latest'
    }

    stages {

        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                bat '''
                docker build -t %IMAGE_NAME%:%IMAGE_TAG% .
                '''
            }
        }

        stage('Login to Azure Container Registry') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'acr-credentials-1',
                    usernameVariable: 'ACR_USER',
                    passwordVariable: 'ACR_PASS'
                )]) {
                    bat '''
                    echo %ACR_PASS% | docker login %ACR_LOGIN_SERVER% ^
                    --username %ACR_USER% ^
                    --password-stdin
                    '''
                }
            }
        }

        stage('Tag Docker Image') {
            steps {
                bat '''
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                '''
            }
        }

        stage('Push Image to ACR') {
            steps {
                bat '''
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                '''
            }
        }
    }

    post {
        success {
            echo 'Pipeline SUCCESS: Image pushed to Azure Container Registry'
        }
        failure {
            echo 'Pipeline FAILED: Check logs'
        }
    }
}