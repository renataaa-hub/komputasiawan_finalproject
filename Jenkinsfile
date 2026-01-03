pipeline {
    agent any

    options {
        skipDefaultCheckout(true)
        timestamps()
    }

    environment {
        ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
        IMAGE_NAME       = 'penaawan-app'
        IMAGE_TAG        = 'init'   // placeholder biar nggak null
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
                    // Ambil short commit (aman di Windows)
                    def commitShort = ''
                    if (env.GIT_COMMIT?.trim()) {
                        commitShort = env.GIT_COMMIT.trim().take(7)
                    } else {
                        bat '@git rev-parse --short HEAD > .gitshort'
                        commitShort = readFile('.gitshort').trim()
                    }
                    if (!commitShort) { commitShort = 'nogit' }

                    // Tag unik per build
                    env.IMAGE_TAG = "${env.BUILD_NUMBER}-${commitShort}"

                    echo "BUILD_NUMBER : ${env.BUILD_NUMBER}"
                    echo "GIT_COMMIT   : ${env.GIT_COMMIT}"
                    echo "commitShort  : ${commitShort}"
                    echo "Using IMAGE_TAG: ${env.IMAGE_TAG}"

                    // Fail-fast kalau kosong
                    if (!env.IMAGE_TAG?.trim()) {
                        error("IMAGE_TAG kosong/null. Stop build.")
                    }

                    // Biar enak tracking di UI Jenkins
                    currentBuild.displayName = "#${env.BUILD_NUMBER} ${commitShort}"
                    currentBuild.description = "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                bat 'echo Building %IMAGE_NAME%:%IMAGE_TAG%'
                bat 'docker build -t %IMAGE_NAME%:%IMAGE_TAG% .'
            }
        }

        stage('Login to ACR') {
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

        stage('Tag Image') {
            steps {
                bat '''
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                docker tag %IMAGE_NAME%:%IMAGE_TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                '''
            }
        }

        stage('Push to ACR') {
            steps {
                bat '''
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%IMAGE_TAG%
                docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
                '''
            }
        }

        stage('Output for Server Admin') {
            steps {
                echo "=== SEND THIS TO SERVER ADMIN ==="
                echo "ACR   : ${env.ACR_LOGIN_SERVER}"
                echo "Image : ${env.IMAGE_NAME}"
                echo "Tag versioned: ${env.IMAGE_TAG}"
                echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
                echo "Tag latest   : latest"
                echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
            }
        }
    }

    post {
        success {
            echo "SUCCESS: CI complete, image ready in ACR."
        }
        failure {
            echo "FAILED: check logs."
        }
        always {
            // Opsional: bersihin workspace supaya gak numpuk
            cleanWs(deleteDirs: true, disableDeferredWipeout: true)
        }
    }
}
