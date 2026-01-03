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
    REPO_URL         = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    BRANCH           = 'main'

    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'


    GIT_CRED_ID      = 'github-pat'
    ACR_CRED_ID      = 'acrPenaAwan2025'

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
          git --version
          docker version
        '''
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          def sha = (env.GIT_COMMIT ?: "").trim()
          if (sha.length() >= 7) {
            env.TAG_VERSIONED = sha.substring(0, 7)
          } else {
            env.TAG_VERSIONED = "init"
          }
          echo "TAG_VERSIONED updated => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          $acr  = "${env:ACR_LOGIN_SERVER}".Trim()
          $name = "${env:IMAGE_NAME}".Trim()
          $tag  = "${env:TAG_VERSIONED}".Trim()

          if ([string]::IsNullOrWhiteSpace($acr))  { throw "ACR_LOGIN_SERVER empty" }
          if ([string]::IsNullOrWhiteSpace($name)) { throw "IMAGE_NAME empty" }
          if ([string]::IsNullOrWhiteSpace($tag))  { throw "TAG_VERSIONED empty" }

          # PENTING: pakai ${name} biar ':' tidak bikin parser error
          $img1 = "$acr/${name}:$tag"
          $img2 = "$acr/${name}:latest"

          Write-Host "Building:"
          Write-Host " - $img1"
          Write-Host " - $img2"

          docker build --pull -t $img1 -t $img2 .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }

          docker images | Select-String $name
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

            $acr  = "${env:ACR_LOGIN_SERVER}".Trim()
            $name = "${env:IMAGE_NAME}".Trim()
            $tag  = "${env:TAG_VERSIONED}".Trim()

            $user = "${env:ACR_USER}".Trim()
            $pass = "${env:ACR_PASS}".Trim()

            $img1 = "$acr/${name}:$tag"
            $img2 = "$acr/${name}:latest"

            Write-Host "ACR=$acr"
            Write-Host "USER=$user"
            Write-Host "PASS_LEN=$($pass.Length)"

            docker logout $acr | Out-Null

            # Anti newline/CRLF issue (lebih stabil daripada pipe langsung)
            $tmp = Join-Path $env:TEMP "acr_pass.txt"
            Set-Content -Path $tmp -Value $pass -NoNewline
            Get-Content $tmp -Raw | docker login $acr --username $user --password-stdin
            Remove-Item $tmp -Force

            if ($LASTEXITCODE -ne 0) { throw "ACR login failed" }

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
    failure {
      echo "FAILED ❌: Check logs above."
    }
  }
}
