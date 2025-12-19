pipeline {
    agent any

    stages {

        stage('Checkout Code') {
            steps {
                echo 'Checkout source code from GitHub'
                checkout scm
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker images'
                sh 'docker-compose build'
            }
        }

        stage('Run Container') {
            steps {
                echo 'Running containers'
                sh 'docker-compose up -d'
            }
        }

        stage('Check Status') {
            steps {
                sh 'docker ps'
            }
        }
    }

    post {
        success {
            echo 'Pipeline berhasil dijalankan'
        }
        failure {
            echo 'Pipeline gagal'
        }
    }
}
