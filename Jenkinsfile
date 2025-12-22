pipeline {
  agent any

  environment {
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'
    IMAGE_NAME       = 'penaawan-app'
    TAG              = "${BUILD_NUMBER}"
    CONTAINER_NAME   = 'penaawan_app'
    APP_PORT         = '80'   // publish port host
    CONTAINER_PORT   = '80'   // apache di image kamu listen 80
  }

  options {
    timestamps()
    timeout(time: 30, unit: 'MINUTES')
  }

  stages {

    stage('Checkout SCM') {
      steps {
        checkout scm
      }
    }

    stage('Build Docker Image') {
      steps {
        bat """
          docker build -t %IMAGE_NAME%:%TAG% .
          docker tag %IMAGE_NAME%:%TAG% %IMAGE_NAME%:latest
        """
      }
    }

    stage('Login to ACR') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: 'acr-credentials-1',
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          bat """
            echo %ACR_PASS% | docker login %ACR_LOGIN_SERVER% --username %ACR_USER% --password-stdin
          """
        }
      }
    }

    stage('Tag Image for ACR') {
      steps {
        bat """
          docker tag %IMAGE_NAME%:%TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%TAG%
          docker tag %IMAGE_NAME%:%TAG% %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
        """
      }
    }

    stage('Push Image to ACR') {
      steps {
        bat """
          docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%TAG%
          docker push %ACR_LOGIN_SERVER%/%IMAGE_NAME%:latest
        """
      }
    }

    stage('Deploy - Pull & Run Container (No .env file)') {
      steps {

        // Ambil secret dari Jenkins Credentials (buat dulu ya di Jenkins)
        withCredentials([
          string(credentialsId: 'laravel-app-key', variable: 'APP_KEY'),
          string(credentialsId: 'azure-mysql-password', variable: 'DB_PASSWORD'),
          string(credentialsId: 'midtrans-server-key', variable: 'MIDTRANS_SERVER_KEY'),
          string(credentialsId: 'midtrans-client-key', variable: 'MIDTRANS_CLIENT_KEY')
        ]) {

          bat """
            echo === Stop & remove old container (if exists) ===
            docker rm -f %CONTAINER_NAME% 2>nul || exit /b 0

            echo === Pull latest image from ACR ===
            docker pull %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%TAG%

            echo === Run container (inject env via docker run) ===
            docker run -d --name %CONTAINER_NAME% ^
              -p %APP_PORT%:%CONTAINER_PORT% ^
              --restart unless-stopped ^
              -e APP_NAME=Laravel ^
              -e APP_ENV=production ^
              -e APP_DEBUG=false ^
              -e APP_URL=http://localhost ^
              -e APP_KEY=%APP_KEY% ^
              -e LOG_CHANNEL=stack ^
              -e LOG_LEVEL=info ^
              -e DB_CONNECTION=mysql ^
              -e DB_HOST=mysql-penaawan.mysql.database.azure.com ^
              -e DB_PORT=3306 ^
              -e DB_DATABASE=penaawan ^
              -e DB_USERNAME=penaawanadmin ^
              -e DB_PASSWORD=%DB_PASSWORD% ^
              -e SESSION_DRIVER=database ^
              -e CACHE_STORE=database ^
              -e QUEUE_CONNECTION=database ^
              -e MIDTRANS_SERVER_KEY=%MIDTRANS_SERVER_KEY% ^
              -e MIDTRANS_CLIENT_KEY=%MIDTRANS_CLIENT_KEY% ^
              -e MIDTRANS_IS_PRODUCTION=false ^
              -e MIDTRANS_IS_SANITIZED=true ^
              -e MIDTRANS_IS_3DS=true ^
              %ACR_LOGIN_SERVER%/%IMAGE_NAME%:%TAG%

            echo === (Optional) Run migrations ===
            docker exec %CONTAINER_NAME% php artisan migrate --force
          """
        }
      }
    }

    stage('Post-Deploy Verification') {
      steps {
        bat """
          echo === Container status ===
          docker ps --filter "name=%CONTAINER_NAME%"

          echo === Health check HTTP (localhost) ===
          powershell -Command "try { (Invoke-WebRequest -UseBasicParsing http://localhost:%APP_PORT% -TimeoutSec 15).StatusCode } catch { exit 1 }"
        """
      }
    }
  }

  post {
    success {
      echo "SUCCESS: Build+Push+Deploy done. TAG=${BUILD_NUMBER}"
    }
    failure {
      echo "FAILED: Check Jenkins console output."
    }
    always {
      bat "docker logout %ACR_LOGIN_SERVER% || exit /b 0"
    }
  }
}
