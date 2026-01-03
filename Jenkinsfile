pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)
    disableConcurrentBuilds()
  }

  environment {
    // === FIXED CONFIG (tidak perlu diubah) ===
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    TAG_VERSIONED    = 'init'
    ACR_CRED_ID      = 'acr-credentials-3'
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Compute Tag') {
      steps {
        powershell '''
          $gitShort = (git rev-parse --short=7 HEAD).Trim()
          Write-Host "GIT_SHORT=$gitShort"
          # simpan jadi env supaya bisa dipakai stage lain
          $env:GIT_SHORT = $gitShort
          Write-Host "Computed TAG_VERSIONED=$env:TAG_VERSIONED, GIT_SHORT=$env:GIT_SHORT"
        '''
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          docker version

          $imgVersioned = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
          $imgLatest    = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

          Write-Host "Building:"
          Write-Host " - $imgVersioned"
          Write-Host " - $imgLatest"

          docker build -t $imgVersioned -t $imgLatest .
        '''
      }
    }

    stage('Login to ACR & Push') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: "${env.ACR_CRED_ID}",
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          powershell '''
            $ErrorActionPreference = "Stop"

            # Login ke ACR (pakai password-stdin biar aman)
            $env:ACR_PASS | docker login $env:ACR_LOGIN_SERVER -u $env:ACR_USER --password-stdin

            $imgVersioned = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
            $imgLatest    = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

            docker push $imgVersioned
            docker push $imgLatest

            docker logout $env:ACR_LOGIN_SERVER | Out-Null
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        powershell '''
          Write-Host "=== SEND THIS TO SERVER ADMIN ==="
          Write-Host ("ACR   : {0}" -f $env:ACR_LOGIN_SERVER)
          Write-Host ("Image : {0}" -f $env:IMAGE_NAME)
          Write-Host ("Tag versioned: {0}" -f $env:TAG_VERSIONED)
          Write-Host ("Full  : {0}/{1}:{2}" -f $env:ACR_LOGIN_SERVER, $env:IMAGE_NAME, $env:TAG_VERSIONED)
          Write-Host ("Tag latest   : latest")
          Write-Host ("Full  : {0}/{1}:latest" -f $env:ACR_LOGIN_SERVER, $env:IMAGE_NAME)
        '''
      }
    }
  }

  post {
    always {
      cleanWs()
    }
    success {
      echo 'SUCCESS: CI complete, image ready in ACR.'
    }
    failure {
      echo 'FAILED: CI failed. Check build/push logs above.'
    }
  }
}
