pipeline {
    agent any

    options {
        skipDefaultCheckout(true)
        timestamps()
    }

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
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
                    def raw = bat(script: '@git rev-parse --short HEAD', returnStdout: true).trim()
                    def commitShort = raw.readLines().last().trim()

                    if (!commitShort) { commitShort = "nogit" }
                    commitShort = commitShort.replaceAll(/[^0-9A-Za-z_.-]/, '')

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
                    credentialsId: 'acrPenaAwan2025',
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

        stage('Tag Image') {
            steps {
                bat """
                docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}
                docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest
                """
            }
        }

        stage('Push to ACR') {
            steps {
                bat """
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
                echo "ACR: ${env.ACR_LOGIN_SERVER}"
                echo "Image: ${env.IMAGE_NAME}"
                echo "Tag latest: latest"
                echo "Tag versioned: ${env.IMAGE_TAG}"
                echo "Full: ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
            }
        }
    }

    post {
        success { echo "SUCCESS: CI complete, image ready in ACR." }
        failure { echo "FAILED: check logs." }
    }
}