pipeline {
    agent any

    environment {
        ACR_NAME = "acrpenaawan2025"
        ACR_LOGIN_SERVER = "acrpenaawan2025.azurecr.io"
        IMAGE_NAME = "laravel-app"
        IMAGE_TAG = "latest"
    }

    stages {

        stage('Checkout SCM') {
            steps {
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                echo "Building Docker image..."
                bat """
                docker build -t %IMAGE_NAME%:%IMAGE_TAG% .
                """
            }
        }

        stage('Login to Azure Container Registry') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'acr-credentials',
                    usernameVariable: 'ACR_USER',
                    passwordVariable: 'ACR_PASS'
                )]) {
                    bat """
                    docker login %ACR_LOGIN_SERVER% -u %ACR_USER% -p %ACR_PASS%
                    """
                }
            }
        }

        stage('Tag Docker Image') {
            steps {
                bat """
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                """
            }
        }

        stage('Push Image to ACR') {
            steps {
                bat """
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                """
            }
        }
    }

    post {
        success {
            echo "Docker image successfully pushed to ACR"
        }
        failure {
            echo "Pipeline failed. Please check the logs."
        }
    }
}
