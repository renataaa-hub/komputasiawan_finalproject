pipeline {
  agent any
  options { timestamps() }

  environment {
    // =========================
    // <<< GANTI INI >>>
    // =========================

    // ACR login server (tanpa https)
    // contoh: acrpenaawan2025.azurecr.io
    ACR_LOGIN_SERVER = 'acrpenaawan2025.azurecr.io'   // <<< GANTI INI kalau beda

    // nama image di ACR
    // contoh: penaawan-app
    IMAGE_NAME = 'penaawan-app'                       // <<< GANTI INI kalau beda

    // Jenkins Credentials ID (Username with password) untuk login ACR
    // (Jenkins > Manage Credentials > add "Username with password")
    ACR_CRED_ID = 'acrPenaAwan2025'                          // <<< GANTI INI sesuai credential ID kamu

    // =========================
    // END CONFIG
    // =========================
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Compute Tag') {
      steps {
        script {
          // Tag versi = git commit short (8 char)
          // kalau gagal ambil git info, fallback ke build number
          def sha = sh(script: "git rev-parse --short=8 HEAD", returnStdout: true).trim()
          if (!sha) { sha = "b${env.BUILD_NUMBER}" }

          env.VERSION_TAG = sha
          env.FULL_IMAGE_VERSIONED = "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:${env.VERSION_TAG}"
          env.FULL_IMAGE_LATEST    = "${env.ACR_LOGIN_SERVER}/${env.IMAGE_NAME}:latest"

          echo "VERSION_TAG           = ${env.VERSION_TAG}"
          echo "FULL_IMAGE_VERSIONED  = ${env.FULL_IMAGE_VERSIONED}"
          echo "FULL_IMAGE_LATEST     = ${env.FULL_IMAGE_LATEST}"
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        sh """
          set -eux
          docker build -t ${FULL_IMAGE_VERSIONED} .
          docker tag ${FULL_IMAGE_VERSIONED} ${FULL_IMAGE_LATEST}
        """
      }
    }

    stage('Login to ACR & Push') {
      steps {
        withCredentials([usernamePassword(
          credentialsId: env.ACR_CRED_ID,
          usernameVariable: 'ACR_USER',
          passwordVariable: 'ACR_PASS'
        )]) {
          sh """
            set -eux
            echo "${ACR_PASS}" | docker login ${ACR_LOGIN_SERVER} -u "${ACR_USER}" --password-stdin
            docker push ${FULL_IMAGE_VERSIONED}
            docker push ${FULL_IMAGE_LATEST}
          """
        }
      }
    }

    stage('Output for Server Admin') {
      steps {
        echo "=== SEND THIS TO SERVER ADMIN ==="
        echo "ACR   : ${ACR_LOGIN_SERVER}"
        echo "Image : ${IMAGE_NAME}"
        echo "Tag versioned: ${VERSION_TAG}"
        echo "Full  : ${FULL_IMAGE_VERSIONED}"
        echo "Tag latest   : latest"
        echo "Full  : ${FULL_IMAGE_LATEST}"
      }
    }
  }

  post {
    always {
      sh """
        set +e
        docker logout ${ACR_LOGIN_SERVER}
        true
      """
      cleanWs()
    }
    success {
      echo "SUCCESS: CI complete, image ready in ACR."
    }
    failure {
      echo "FAILED: CI failed. Check build/push logs above."
    }
  }
}
