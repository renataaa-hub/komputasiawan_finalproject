pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)
    disableConcurrentBuilds()
  }

  environment {
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
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
          def sha = ''
          if (env.GIT_COMMIT?.trim()) {
            sha = env.GIT_COMMIT.trim().take(7)
          } else {
            // fallback kalau env.GIT_COMMIT kosong
            sha = powershell(returnStdout: true, script: 'git rev-parse --short HEAD').trim()
          }
          env.IMAGE_TAG = "${env.BUILD_NUMBER}-${sha}"
          echo "IMAGE_TAG = ${env.IMAGE_TAG}"
          currentBuild.displayName = "#${env.BUILD_NUMBER} ${sha}"
        }
      }
    }

    stage('Build Image') {
      steps {
        powershell """
          \$ErrorActionPreference = "Stop"
          docker version
          docker build -t ${env.IMAGE_NAME}:${env.IMAGE_TAG} .
          docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} ${env.IMAGE_NAME}:latest
          docker images ${env.IMAGE_NAME} --format "table {{.Repository}}\\t{{.Tag}}\\t{{.ID}}\\t{{.Size}}"
        """
      }
    }

    stage('Login + Tag + Push to ACR') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'acr-admin-penaawan',   // <-- GANTI kalau ID kamu beda
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          powershell """
            \$ErrorActionPreference = "Stop"

            \$acr = "${env.ACR_LOGIN_SERVER}".Trim()
            \$user = "\$env:ACR_USER"
            \$pass = "\$env:ACR_PASS"

            docker logout \$acr | Out-Null

            # Login aman (tidak rusak karena karakter spesial)
            \$pass | docker login \$acr --username \$user --password-stdin
            if (\$LASTEXITCODE -ne 0) { throw "ACR login failed" }

            \$imgVer = "\$acr/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
            \$imgLat = "\$acr/${env.IMAGE_NAME}:latest"

            docker tag ${env.IMAGE_NAME}:${env.IMAGE_TAG} \$imgVer
            docker tag ${env.IMAGE_NAME}:latest \$imgLat

            docker push \$imgVer
            if (\$LASTEXITCODE -ne 0) { throw "Push failed: \$imgVer" }

            docker push \$imgLat
            if (\$LASTEXITCODE -ne 0) { throw "Push failed: \$imgLat" }

            Write-Host "Pushed:"
            Write-Host " - \$imgVer"
            Write-Host " - \$imgLat"
          """
        }
      }
    }

    stage('Output') {
      steps {
        echo "ACR   : ${env.ACR_LOGIN_SERVER}"
        echo "Image : ${env.IMAGE_NAME}"
        echo "Tag   : ${env.IMAGE_TAG}"
        echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.IMAGE_TAG}"
      }
    }
  }

  post {
    success { echo "SUCCESS ✅: image pushed to ACR." }
    failure { echo "FAILED ❌: check logs above." }
    always  { cleanWs() }
  }
}
