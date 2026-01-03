pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)
    disableConcurrentBuilds()
    timeout(time: 30, unit: 'MINUTES')
    buildDiscarder(logRotator(numToKeepStr: '20'))
  }

  environment {
    // ==== UBAH SESUAI PUNYA KAMU ====
    REPO_URL         = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    BRANCH           = 'main'

    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'

    // Credential IDs di Jenkins (Manage Credentials)
    GIT_CRED_ID      = 'github-pat'
    ACR_CRED_ID      = 'acr-admin-penaawan'

    // default, nanti di-update dari commit
    TAG_VERSIONED    = 'init'
  }

  stages {
    stage('Checkout') {
      steps {
        echo "Checkout ${env.REPO_URL} branch ${env.BRANCH}"
        checkout([
          $class: 'GitSCM',
          branches: [[name: "*/${env.BRANCH}"]],
          userRemoteConfigs: [[
            url: env.REPO_URL,
            credentialsId: env.GIT_CRED_ID
          ]]
        ])
      }
    }

    stage('Sanity Check Vars') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"
          Write-Host "ACR_LOGIN_SERVER=$env:ACR_LOGIN_SERVER"
          Write-Host "IMAGE_NAME=$env:IMAGE_NAME"
          Write-Host "TAG_VERSIONED=$env:TAG_VERSIONED"
          Write-Host "GIT_CRED_ID=$env:GIT_CRED_ID"
          Write-Host "ACR_CRED_ID=$env:ACR_CRED_ID"
          git --version
          docker version
        '''
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          def shortSha = powershell(returnStdout: true, script: 'git rev-parse --short HEAD').trim()
          env.TAG_VERSIONED = shortSha
          echo "TAG_VERSIONED updated => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          $img1 = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
          $img2 = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

          Write-Host "Building:"
          Write-Host " - $img1"
          Write-Host " - $img2"

          docker build --pull -t $img1 -t $img2 .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }

          docker images | Select-String $env:IMAGE_NAME
        '''
      }
    }

    stage('Login to ACR & Push') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: env.ACR_CRED_ID,
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          powershell '''
            $ErrorActionPreference = "Stop"

            $img1 = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
            $img2 = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

            Write-Host "Login ACR: $env:ACR_LOGIN_SERVER as $env:ACR_USER"

            docker logout $env:ACR_LOGIN_SERVER | Out-Null

            # password-stdin supaya aman (nggak kena masalah newline)
            $env:ACR_PASS | docker login $env:ACR_LOGIN_SERVER --username $env:ACR_USER --password-stdin
            if ($LASTEXITCODE -ne 0) { throw "ACR login failed" }

            Write-Host "Pushing:"
            Write-Host " - $img1"
            Write-Host " - $img2"

            docker push $img1
            if ($LASTEXITCODE -ne 0) { throw "Push failed: $img1" }

            docker push $img2
            if ($LASTEXITCODE -ne 0) { throw "Push failed: $img2" }
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "DONE ✅"
        echo "Image pushed:"
        echo "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}"
        echo "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
      }
    }
  }

  post {
    always {
      echo "Pipeline finished. Cleaning workspace..."
      cleanWs()
    }
    success {
      echo "SUCCESS ✅"
    }
    failure {
      echo "FAILED ❌: Check logs above (build/push stage)."
    }
  }
}
