pipeline {
  agent any

  // OPTIONAL: biar bisa ganti tag manual dari Jenkins UI
  parameters {
    string(name: 'VERSION_TAG', defaultValue: 'init', description: 'CHANGE_ME: tag versi image (contoh: init, v1, prod, dll)')
  }

  environment {
    // ====== CHANGE_ME: SESUAIKAN ======
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'      // CHANGE_ME: login server ACR kamu
    IMAGE_NAME       = 'penaawan-app'                    // CHANGE_ME: nama image kamu
    ACR_CRED_ID      = 'acr-credentials-1'               // CHANGE_ME: ID credential ACR (username/password) di Jenkins
    // =================================
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          // Tag versioned: dari parameter
          env.TAG_VERSIONED = params.VERSION_TAG?.trim()
          if (!env.TAG_VERSIONED) { env.TAG_VERSIONED = "init" }

          // Tag tambahan: short commit (optional)
          def commit = powershell(returnStdout: true, script: 'git rev-parse --short HEAD').trim()
          env.GIT_SHORT = commit

          echo "Computed TAG_VERSIONED=${env.TAG_VERSIONED}, GIT_SHORT=${env.GIT_SHORT}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          docker version
          docker build -t "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED" .
          docker tag "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED" "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

          Write-Host "Build done."
        '''
      }
    }

    stage('Login to ACR & Push') {
      steps {
        withCredentials([usernamePassword(credentialsId: env.ACR_CRED_ID, usernameVariable: 'ACR_USER', passwordVariable: 'ACR_PASS')]) {
          powershell '''
            $ErrorActionPreference = "Stop"

            # Login ACR
            docker login $env:ACR_LOGIN_SERVER -u $env:ACR_USER -p $env:ACR_PASS

            # Push
            docker push "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
            docker push "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

            Write-Host "Push done."
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "=== SEND THIS TO SERVER ADMIN ==="
        echo "ACR   : ${env.ACR_LOGIN_SERVER}"
        echo "Image : ${env.IMAGE_NAME}"
        echo "Tag versioned: ${env.TAG_VERSIONED}"
        echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}"
        echo "Tag latest   : latest"
        echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
      }
    }
  }

  post {
    always {
      cleanWs()
    }
    success {
      echo "SUCCESS: CI complete, image ready in ACR."
    }
    failure {
      echo "FAILED: CI failed. Check build/push logs above."
    }
  }
}
