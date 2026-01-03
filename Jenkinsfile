pipeline {
  agent any

  options {
    timestamps()
    disableConcurrentBuilds()
    ansiColor('xterm')
  }

  environment {
    // === GIT / SOURCE ===
    REPO_URL    = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    BRANCH      = 'main'
    GIT_CRED_ID = 'github-pat'          // Jenkins Credential untuk GitHub (Username + PAT)

    // === ACR / IMAGE ===
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    ACR_CRED_ID      = 'acr-admin-penaawan' // Jenkins Credential untuk ACR (Username + Password)
  }

  stages {

    stage('Checkout') {
      steps {
        echo "Checkout ${env.REPO_URL} branch ${env.BRANCH}"

        // Ini yang disebut "Checkout source code" di pipeline
        checkout([
          $class: 'GitSCM',
          branches: [[name: "*/${env.BRANCH}"]],
          doGenerateSubmoduleConfigurations: false,
          extensions: [
            [$class: 'CleanBeforeCheckout'],
            [$class: 'PruneStaleBranch']
          ],
          userRemoteConfigs: [[
            url: env.REPO_URL,
            credentialsId: env.GIT_CRED_ID
          ]]
        ])
      }
    }

    stage('Sanity Check') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"
          Write-Host "== Versions =="
          git --version
          docker version
          docker info | Select-String -Pattern "Server Version|OSType|Operating System" -SimpleMatch
          Write-Host ""
          Write-Host "== Vars =="
          Write-Host ("ACR_LOGIN_SERVER=" + $env:ACR_LOGIN_SERVER)
          Write-Host ("IMAGE_NAME=" + $env:IMAGE_NAME)

          if ([string]::IsNullOrWhiteSpace($env:ACR_LOGIN_SERVER)) { throw "ACR_LOGIN_SERVER kosong" }
          if ([string]::IsNullOrWhiteSpace($env:IMAGE_NAME))       { throw "IMAGE_NAME kosong" }
        '''
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          def shortHash = powershell(returnStdout: true, script: 'git rev-parse --short=7 HEAD').trim()
          env.TAG_VERSIONED = shortHash ? shortHash : "build-${env.BUILD_NUMBER}"
          echo "TAG_VERSIONED = ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          $imgVersioned = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
          $imgLatest    = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

          Write-Host "Building images:"
          Write-Host " - $imgVersioned"
          Write-Host " - $imgLatest"

          docker build --pull -t "$imgVersioned" -t "$imgLatest" .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }

          docker images "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME" --format "table {{.Repository}}\t{{.Tag}}\t{{.ID}}\t{{.Size}}"
        '''
      }
    }

    stage('Login to ACR & Push (NO-NEWLINE)') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: env.ACR_CRED_ID,
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          powershell '''
            $ErrorActionPreference = "Stop"

            $registry = "$env:ACR_LOGIN_SERVER"
            $user     = "$env:ACR_USER"

            # FIX Windows: hilangkan newline/spasi biar docker login gak gagal
            $pass = ("$env:ACR_PASS").Trim()

            Write-Host "=== DEBUG ACR ==="
            Write-Host ("Registry : " + $registry)
            Write-Host ("User     : " + $user)
            Write-Host ("PassLen  : " + $pass.Length)

            if ([string]::IsNullOrWhiteSpace($registry)) { throw "ACR_LOGIN_SERVER kosong" }
            if ([string]::IsNullOrWhiteSpace($user))     { throw "ACR username kosong (cek credential Jenkins)" }
            if ([string]::IsNullOrWhiteSpace($pass))     { throw "ACR password kosong (cek credential Jenkins)" }

            # Bersihin sesi login lama
            docker logout "$registry" | Out-Null

            # Login ACR
            $pass | docker login "$registry" --username "$user" --password-stdin
            $exit = $LASTEXITCODE
            if ($exit -ne 0) {
              throw "ACR login failed (exit=$exit). Paling sering: Admin user ACR OFF / password salah / ACR networking dibatasi / credential bukan untuk registry ini."
            }

            # Push
            $imgVersioned = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
            $imgLatest    = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

            docker push "$imgVersioned"
            if ($LASTEXITCODE -ne 0) { throw "Push versioned failed" }

            docker push "$imgLatest"
            if ($LASTEXITCODE -ne 0) { throw "Push latest failed" }

            Write-Host "Push OK:"
            Write-Host (" - " + $imgVersioned)
            Write-Host (" - " + $imgLatest)

            docker logout "$registry" | Out-Null
          '''
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "DONE ✅ Image pushed to: ${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.TAG_VERSIONED} and :latest"
      }
    }
  }

  post {
    always {
      powershell '''
        # best-effort cleanup
        try { docker logout "$env:ACR_LOGIN_SERVER" | Out-Null } catch {}
      '''
      cleanWs()
    }
    failure {
      echo "FAILED ❌ Lihat stage yang merah (biasanya ACR login / push)."
    }
  }
}
