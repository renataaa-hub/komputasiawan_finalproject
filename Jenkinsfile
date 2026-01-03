pipeline {
  agent any

  options {
    timestamps()
    skipDefaultCheckout(true)
    disableConcurrentBuilds()
  }

  environment {
    // ====== GITHUB CHECKOUT ======
    REPO_URL   = 'https://github.com/renataaa-hub/komputasiawan_finalproject.git'
    BRANCH     = 'main'
    GIT_CRED_ID = 'github-pat'              // <-- credential GitHub (username + PAT)

    // ====== ACR ======
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    ACR_CRED_ID      = 'acr-admin-penaawan' // <-- credential ACR (username ACR + password Access Keys)

    // ====== TAG ======
    TAG_VERSIONED    = 'init'              // nanti di-update dari git short hash kalau bisa
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
          // Ambil git short hash; kalau gagal, fallback BUILD_NUMBER
          def shortHash = powershell(returnStdout: true, script: 'git rev-parse --short HEAD').trim()
          if (shortHash) {
            env.TAG_VERSIONED = shortHash
          } else {
            env.TAG_VERSIONED = "build-${env.BUILD_NUMBER}"
          }
          echo "TAG_VERSIONED updated => ${env.TAG_VERSIONED}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        powershell '''
          $ErrorActionPreference = "Stop"

          $imgVersioned = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:$env:TAG_VERSIONED"
          $imgLatest    = "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME:latest"

          Write-Host "Building:"
          Write-Host " - $imgVersioned"
          Write-Host " - $imgLatest"

          docker build -t $imgVersioned -t $imgLatest .
          if ($LASTEXITCODE -ne 0) { throw "Docker build failed" }

          docker image ls "$env:ACR_LOGIN_SERVER/$env:IMAGE_NAME" --format "table {{.Repository}}\t{{.Tag}}\t{{.ID}}\t{{.Size}}"
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

            $registry = $env:ACR_LOGIN_SERVER
            $user     = $env:ACR_USER
            $pass     = $env:ACR_PASS

            # Trim whitespace (kadang dari copy paste)
            $pass = $pass.Trim()

            Write-Host "=== DEBUG ACR ==="
            Write-Host "Registry : $registry"
            Write-Host "User     : $user"
            Write-Host ("PassLen  : " + $pass.Length)

            # Cek endpoint /v2/ (harus reachable; biasanya 401 kalau belum login)
            try {
              $r = Invoke-WebRequest -Uri ("https://{0}/v2/" -f $registry) -UseBasicParsing -Method Get -TimeoutSec 10
              Write-Host ("HTTP /v2/ status: " + $r.StatusCode)
            } catch {
              Write-Host ("HTTP /v2/ check (expected 401 before login). Message: " + $_.Exception.Message)
            }

            # Bersihin sesi login lama
            docker logout $registry | Out-Null

            # ====== LOGIN TANPA NEWLINE (paling aman di Windows) ======
            $psi = New-Object System.Diagnostics.ProcessStartInfo
            $psi.FileName = "docker"
            $psi.Arguments = "login $registry --username $user --password-stdin"
            $psi.RedirectStandardInput  = $true
            $psi.RedirectStandardOutput = $true
            $psi.RedirectStandardError  = $true
            $psi.UseShellExecute = $false
            $psi.CreateNoWindow  = $true

            $p = New-Object System.Diagnostics.Process
            $p.StartInfo = $psi
            [void]$p.Start()

            # tulis password TANPA newline
            $p.StandardInput.Write($pass)
            $p.StandardInput.Close()

            $out = $p.StandardOutput.ReadToEnd()
            $err = $p.StandardError.ReadToEnd()
            $p.WaitForExit()

            if ($out) { Write-Host $out }
            if ($p.ExitCode -ne 0) {
              if ($err) { Write-Host $err }
              throw "ACR login failed (exit=$($p.ExitCode))"
            }

            # Push
            $imgVersioned = "$registry/$env:IMAGE_NAME:$env:TAG_VERSIONED"
            $imgLatest    = "$registry/$env:IMAGE_NAME:latest"

            docker push $imgVersioned
            if ($LASTEXITCODE -ne 0) { throw "Push versioned failed" }

            docker push $imgLatest
            if ($LASTEXITCODE -ne 0) { throw "Push latest failed" }

            docker logout $registry | Out-Null
            Write-Host "Push success: $imgVersioned and $imgLatest"
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
      cleanWs()
    }
    failure {
      echo "FAILED: CI failed. Check logs above."
    }
  }
}
