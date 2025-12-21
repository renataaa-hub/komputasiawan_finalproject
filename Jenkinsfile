pipeline {
    agent any

    stages {

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                sh 'docker build -t laravel-app:latest .'
            }
        }

        stage('Login to Azure Container Registry') {
            steps {
                echo 'Login to Azure ACR...'
                withCredentials([usernamePassword(
                    credentialsId: 'acr-credentials',
                    usernameVariable: 'ACR_USER',
                    passwordVariable: 'ACR_PASS'
                )]) {
                    sh '''
                    docker login acrpenaawan2025.azurecr.io \
                    -u $ACR_USER \
                    -p $ACR_PASS
                    '''
                }
            }
        }

        stage('Tag Docker Image') {
            steps {
                echo 'Tagging Docker image...'
                sh '''
                docker tag laravel-app:latest \
                acrpenaawan2025.azurecr.io/laravel-app:v1
                '''
            }
        }

        stage('Push Image to ACR') {
            steps {
                echo 'Pushing image to ACR...'
                sh '''
                docker push acrpenaawan2025.azurecr.io/laravel-app:v1
                '''
            }
        }
    }

    post {
        success {
            echo 'Docker image successfully pushed to Azure Container Registry'
        }
        failure {
            echo 'Pipeline failed. Please check the logs.'
        }
    }
}
