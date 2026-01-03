pipeline {
    agent any

    options {
        skipDefaultCheckout(true)
        timestamps()
    }

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        // JANGAN declare IMAGE_TAG di sini biar nggak nge-lock jadi kosong
    }

    stages {
        stage('Checkout') {
            steps {
                checkout([
                    $class: 'GitSCM',
                    branches: [[name: '*/main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/renataaa-hub/komputasiawan_finalproject.git',
                        credentialsId: 'github-pat'
                    ]]
                ])
            }
        }

        stage('Set Image Tag') {
            steps {
                script {
                    def commitShort = bat(
                        script: 'git rev-parse --short HEAD',
                        returnStdout: true
                    ).trim()

                    if (!commitShort) { commitShort = "nogit" }

                    env.IMAGE_TAG = "${env.BUILD_NUMBER}-${commitShort}"
                    echo "Using IMAGE_TAG: ${env.IMAGE_TAG}"

                    currentBuild.displayName = "#${env.BUILD_NUMBER} ${commitShort}"
                    currentBuild.description = "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                bat "docker build -t ${env.IMAGE_NAME}:${env.IMAGE_TAG} ."
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
                    docker logout ${env.ACR_LOGIN_SERVER} >NUL 2>&1
                    echo %ACR_PASS% | docker login ${env.ACR_LOGIN_SERVER} --username %ACR_USER% --password-stdin
                    if %ERRORLEVEL% NEQ 0 exit /b 1
                    """
                }
            }
        }

        stage('Tag & Push') {
            steps {
                bat """
                docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}
                docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest

                docker push ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}
                if %ERRORLEVEL% NEQ 0 exit /b 1

                docker push ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest
                if %ERRORLEVEL% NEQ 0 exit /b 1
                """
            }
        }

        stage('Output for Server Admin') {
            steps {
                echo "=== SEND THIS TO SERVER ADMIN ==="
                echo "Full: ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
                echo "Latest: ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
            }
        }
    }

    post {
        success { echo "SUCCESS ✅: image pushed to ACR." }
        failure { echo "FAILED ❌: check logs." }
    }
}
