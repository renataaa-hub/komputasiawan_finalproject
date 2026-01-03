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
    ACR_CRED_ID      = 'acr-credentials-fix1' // pastikan ini ID yang bener
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
          Write-Host "ACR_LOGIN_SERVER=$env:ACR_LOGIN_SERVER"
          Write-Host "IMAGE_NAME=$env:IMAGE_NAME"
          Write-Host "TAG_VERSIONED=$env:TAG_VERSIONED"
          Write-Host "ACR_CRED_ID=$env:ACR_CRED_ID"

          if ([string]::IsNullOrWhiteSpace($env:ACR_LOGIN_SERVER)) { throw "ACR_LOGIN_SERVER kosong" }
          if ([string]::IsNullOrWhiteSpace($env:IMAGE_NAME)) { throw "IMAGE_NAME kosong" }
          if ([string]::IsNullOrWhiteSpace($env:ACR_CRED_ID)) { throw "ACR_CRED_ID kosong" }
        '''
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          // ini yang bikin TAG_VERSIONED bener-bener kebawa ke stage berikutnya
          env.TAG_VERSIONED = powershell(returnStdout: true, script: '(git rev-parse --short=7 HEAD).Trim()').trim()
          echo "TAG_VERSIONED updated => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          docker version

          Write-Host "ACR_LOGIN_SERVER=$env:ACR_LOGIN_SERVER"
          Write-Host "IMAGE_NAME=$env:IMAGE_NAME"
          Write-Host "TAG_VERSIONED=$env:TAG_VERSIONED"

          $imgVersioned = "$($env:ACR_LOGIN_SERVER)/$($env:IMAGE_NAME):$($env:TAG_VERSIONED)"
          $imgLatest    = "$($env:ACR_LOGIN_SERVER)/$($env:IMAGE_NAME):latest"

          Write-Host "Building:"
          Write-Host " - $imgVersioned"
          Write-Host " - $imgLatest"

          docker build -t $imgVersioned -t $imgLatest .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }
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

            $imgVersioned = "$($env:ACR_LOGIN_SERVER)/$($env:IMAGE_NAME):$($env:TAG_VERSIONED)"
            $imgLatest    = "$($env:ACR_LOGIN_SERVER)/$($env:IMAGE_NAME):latest"

            Write-Host "Logging into ACR: $env:ACR_LOGIN_SERVER as $env:ACR_USER"
            $env:ACR_PASS | docker login "$env:ACR_LOGIN_SERVER" -u "$env:ACR_USER" --password-stdin
            if ($LASTEXITCODE -ne 0) { throw "ACR login failed" }

            docker push $imgVersioned
            if ($LASTEXITCODE -ne 0) { throw "Push versioned failed" }

            docker push $imgLatest
            if ($LASTEXITCODE -ne 0) { throw "Push latest failed" }

            docker logout "$env:ACR_LOGIN_SERVER" | Out-Null
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        powershell '''
          Write-Host "=== SEND THIS TO SERVER ADMIN ==="
          Write-Host "ACR   : $env:ACR_LOGIN_SERVER"
          Write-Host "Image : $env:IMAGE_NAME"
          Write-Host "Tag versioned: $env:TAG_VERSIONED"
          Write-Host "Full  : $env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
          Write-Host "Tag latest   : latest"
          Write-Host "Full  : $env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"
        '''
      }
    }
  }

  post {
    always { cleanWs() }
    success { echo 'SUCCESS: CI complete, image ready in ACR.' }
    failure { echo 'FAILED: CI failed. Check build/push logs above.' }
  }
}
