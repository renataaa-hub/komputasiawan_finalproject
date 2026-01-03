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
    TAG_VERSIONED    = 'init'
    ACR_CRED_ID      = 'acr-credentials-3'
  }

  stages {

    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Sanity Check Vars') {
      steps {
        powershell '''
          Write-Host "ACR_LOGIN_SERVER=${env:ACR_LOGIN_SERVER}"
          Write-Host "IMAGE_NAME=${env:IMAGE_NAME}"
          Write-Host "TAG_VERSIONED=${env:TAG_VERSIONED}"

          if ([string]::IsNullOrWhiteSpace("${env:ACR_LOGIN_SERVER}")) { throw "ACR_LOGIN_SERVER kosong" }
          if ([string]::IsNullOrWhiteSpace("${env:IMAGE_NAME}")) { throw "IMAGE_NAME kosong" }
          if ([string]::IsNullOrWhiteSpace("${env:TAG_VERSIONED}")) { throw "TAG_VERSIONED kosong" }
        '''
      }
    }

    stage('Compute Tag') {
      steps {
        powershell '''
          $gitShort = (git rev-parse --short=7 HEAD).Trim()
          Write-Host "GIT_SHORT=$gitShort"
        '''
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          docker version

          $imgVersioned = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:${env:TAG_VERSIONED}"
          $imgLatest    = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:latest"

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

            $imgVersioned = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:${env:TAG_VERSIONED}"
            $imgLatest    = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:latest"

            # Login aman (password-stdin)
            $env:ACR_PASS | docker login "${env:ACR_LOGIN_SERVER}" -u "$env:ACR_USER" --password-stdin

            docker push $imgVersioned
            docker push $imgLatest

            docker logout "${env:ACR_LOGIN_SERVER}" | Out-Null
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        powershell '''
          Write-Host "=== SEND THIS TO SERVER ADMIN ==="
          Write-Host "ACR   : ${env:ACR_LOGIN_SERVER}"
          Write-Host "Image : ${env:IMAGE_NAME}"
          Write-Host "Tag versioned: ${env:TAG_VERSIONED}"
          Write-Host "Full  : ${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:${env:TAG_VERSIONED}"
          Write-Host "Tag latest   : latest"
          Write-Host "Full  : ${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:latest"
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
