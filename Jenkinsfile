pipeline {
    agent any

    tools {
        nodejs "Node22" // Configura una instalación de Node.js en Jenkins
        dockerTool 'Dockertool'  // Cambia el nombre de la herramienta según tu configuración en Jenkins
    }

    stages {
        stage('Instalar dependencias') {
            steps {
                sh 'chmod +x -R node_modules/.bin/'
                sh 'npm install'
            }
        }

        stage('Ejecutar tests') {
            steps {
                sh 'npm test'
            }
        }

        stage('Construir Imagen Docker') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                sh 'docker build -t hola-mundo-node:latest .'
            }
        }

        stage('Ejecutar Contenedor Node.js') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                sh '''
                    docker stop hola-mundo-node || true
                    docker rm hola-mundo-node || true
                    docker run -d --name hola-mundo-node -p 3000:3000 hola-mundo-node:latest
                '''
            }
        }
    }
}
 