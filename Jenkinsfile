pipeline {
  agent any

  options {
    timestamps()
    buildDiscarder(logRotator(numToKeepStr: '20'))
  }

  environment {
    // ====== GITHUB ======
    REPO_URL      = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    BRANCH_NAME   = 'main'
    GIT_CRED_ID   = 'github-pat'           // <-- ini HARUS sama dengan ID credential GitHub di Jenkins

    // ====== ACR ======
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    ACR_CRED_ID      = 'acr-admin-penaawan' // <-- ini HARUS sama dengan ID credential ACR di Jenkins

    // Default tag (nanti ditimpa di Compute Tag)
    TAG_VERSIONED = 'init'
  }

  stages {

    stage('Checkout') {
      steps {
        echo "Checkout ${env.REPO_URL} branch ${env.BRANCH_NAME}"
        checkout([
          $class: 'GitSCM',
          branches: [[name: "*/${env.BRANCH_NAME}"]],
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
          def shortSha = powershell(returnStdout: true, script: 'git rev-parse --short=7 HEAD').trim()
          env.TAG_VERSIONED = shortSha
          echo "TAG_VERSIONED updated => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell """
          \$ErrorActionPreference = 'Stop'
          \$imgVersioned = '${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}'
          \$imgLatest    = '${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest'

          Write-Host 'Building:'
          Write-Host " - \$imgVersioned"
          Write-Host " - \$imgLatest"

          docker build -t \$imgVersioned -t \$imgLatest .
          if (\$LASTEXITCODE -ne 0) { throw 'Docker build failed' }

          docker images | findstr ${env.IMAGE_NAME} | Out-Host
        """
      }
    }

    stage('Login to ACR & Push') {
      steps {
        withCredentials([usernamePassword(credentialsId: env.ACR_CRED_ID, usernameVariable: 'ACR_USER', passwordVariable: 'ACR_PASS')]) {
          powershell """
            \$ErrorActionPreference = 'Stop'
            \$registry = '${env.ACR_LOGIN_SERVER}'
            \$imgVersioned = '${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}'
            \$imgLatest    = '${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest'

            # (opsional) bersihin login session lama
            docker logout \$registry | Out-Null

            # FIX Windows: buang CR/LF/spasi di akhir password
            \$pass = "\$env:ACR_PASS".Trim()

            Write-Host "=== DEBUG ACR ==="
            Write-Host "Registry : \$registry"
            Write-Host "User     : \$env:ACR_USER"
            Write-Host ("PassLen  : " + \$pass.Length)

            # docker login via stdin (tanpa newline)
            \$p = Start-Process -FilePath "docker" -ArgumentList @("login", \$registry, "-u", "\$env:ACR_USER", "--password-stdin") -NoNewWindow -PassThru -RedirectStandardInput "pipe" -RedirectStandardError "pipe" -RedirectStandardOutput "pipe"
            \$p.StandardInput.Write(\$pass)
            \$p.StandardInput.Close()
            \$p.WaitForExit()

            \$out = \$p.StandardOutput.ReadToEnd()
            \$err = \$p.StandardError.ReadToEnd()
            if (\$out) { Write-Host \$out }
            if (\$err) { Write-Host \$err }

            if (\$p.ExitCode -ne 0) { throw "ACR login failed (exit=\$($p.ExitCode))" }

            docker push \$imgVersioned
            if (\$LASTEXITCODE -ne 0) { throw "Push versioned failed" }

            docker push \$imgLatest
            if (\$LASTEXITCODE -ne 0) { throw "Push latest failed" }

            docker logout \$registry | Out-Null
          """
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "Image pushed:"
        echo " - ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED}"
        echo " - ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"
      }
    }
  }

  post {
    always {
      echo "Pipeline finished. Cleaning workspace..."
      cleanWs()
    }
    failure {
      echo "FAILED: Check logs above (build/push stage)."
    }
  }
}
