pipeline {
  agent any

  options {
    timestamps()
    disableConcurrentBuilds()
    skipDefaultCheckout(true)
  }

  environment {
    // ACR
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    ACR_CRED_ID      = 'acr-admin-penaawan'   // <-- credential ACR (username+password ACR)

    // GitHub
    GIT_URL          = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    GIT_BRANCH       = '*/main'
    GIT_CRED_ID      = 'github-pat'           // <-- credential GitHub (username+PAT)
  }

  stages {

    stage('Checkout') {
      steps {
        checkout([$class: 'GitSCM',
          branches: [[name: "${env.GIT_BRANCH}"]],
          userRemoteConfigs: [[
            url: "${env.GIT_URL}",
            credentialsId: "${env.GIT_CRED_ID}"
          ]]
        ])
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          def shortHash = powershell(returnStdout: true, script: 'git rev-parse --short=7 HEAD').trim()
          env.TAG_VERSIONED = shortHash
          echo "TAG_VERSIONED set => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          docker version

          $imgVersioned = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:${env:TAG_VERSIONED}"
          $imgLatest    = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:latest"

          Write-Host "Building:"
          Write-Host " - $imgVersioned"
          Write-Host " - $imgLatest"

          docker build -t $imgVersioned -t $imgLatest .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }
        '''
      }
    }

    stage('Login to ACR & Push (DEBUG)') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: "${env.ACR_CRED_ID}",
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          powershell '''
            $ErrorActionPreference = "Stop"

            $reg = "${env:ACR_LOGIN_SERVER}"
            $imgVersioned = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:${env:TAG_VERSIONED}"
            $imgLatest    = "${env:ACR_LOGIN_SERVER}/${env:IMAGE_NAME}:latest"

            Write-Host "=== DEBUG ACR ==="
            Write-Host "Registry : $reg"
            Write-Host "User     : $env:ACR_USER"
            Write-Host "PassLen  : $($env:ACR_PASS.Length)"   # aman, tidak bocorin isi

            # bersihin login session lama
            docker logout $reg 2>$null | Out-Null

            # FIX Windows: pastikan stdin tidak ada newline aneh
            $pass = $env:ACR_PASS
            $pass = $pass -replace "`r",""
            $pass = $pass -replace "`n",""
            $pass = $pass.Trim()

            Write-Host "Logging in..."
            $pass | docker login $reg -u "$env:ACR_USER" --password-stdin
            if ($LASTEXITCODE -ne 0) { throw "ACR login failed (check credential content + ACR access)" }

            Write-Host "Pushing versioned: $imgVersioned"
            docker push $imgVersioned
            if ($LASTEXITCODE -ne 0) { throw "Push versioned failed" }

            Write-Host "Pushing latest: $imgLatest"
            docker push $imgLatest
            if ($LASTEXITCODE -ne 0) { throw "Push latest failed" }

            docker logout $reg | Out-Null
            Write-Host "DONE push."
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "ACR   : ${env.ACR_LOGIN_SERVER}"
        echo "Image : ${env.IMAGE_NAME}"
        echo "Tag   : ${env.TAG_VERSIONED}"
        echo "Full  : ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}"
        echo "Latest: ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
      }
    }
  }

  post {
    always { cleanWs() }
    success { echo 'SUCCESS: build+push complete.' }
    failure { echo 'FAILED: check logs above.' }
  }
}
