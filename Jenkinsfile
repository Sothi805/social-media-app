pipeline {
    agent any

    environment {
        GIT_REPO_URL = "https://github.com/Sothi805/social-media-app.git"
        IMAGE_NAME = "vethsothi/social-media-app"
        DOCKER_CREDENTIALS = "dockerhub_creds"
        REMOTE_USER = "ubuntu"
        REMOTE_HOST = "98.81.79.6"
        REMOTE_PATH = "/home/ubuntu/deploy"
    }

    parameters {
        gitParameter(name: 'TAG', type: 'PT_TAG', defaultValue: '', description: 'Select Git tag to build')
        gitParameter(name: 'BRANCH', type: 'PT_BRANCH', defaultValue: 'main', description: 'Select branch if no tag')
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    if (params.TAG) {
                        echo "Checking out tag: ${params.TAG}"
                        checkout([$class: 'GitSCM',
                            branches: [[name: "refs/tags/${params.TAG}"]],
                            userRemoteConfigs: [[url: env.GIT_REPO_URL]]
                        ])
                    } else {
                        echo "Checking out branch: ${params.BRANCH}"
                        checkout([$class: 'GitSCM',
                            branches: [[name: params.BRANCH]],
                            userRemoteConfigs: [[url: env.GIT_REPO_URL]]
                        ])
                    }
                }
            }
        }

        stage('Build Docker image') {
            steps {
                script {
                    bat """
                        docker build -t ${IMAGE_NAME}:${params.TAG} .
                    """
                    echo "✅ Built image ${IMAGE_NAME}:${params.TAG}"
                }
            }
        }

        stage('Login to Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
                }
            }
        }

        stage('Push image to Docker Hub') {
            steps {
                sh """
                    docker push ${IMAGE_NAME}:${params.TAG}
                """
            }
        }

        stage('Deploy to EC2') {
            steps {
                sshagent(['REMOTE_SSH_KEY']) {
                    sh """
                        scp -o StrictHostKeyChecking=no docker-compose.yml ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/
                        ssh -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} '
                            cd ${REMOTE_PATH}
                            sudo apt-get update -y
                            sudo apt-get install -y docker-compose
                            sudo docker compose down || true
                            sudo docker pull ${IMAGE_NAME}:${params.TAG}
                            sudo docker compose up -d
                        '
                    """
                }
            }
        }
    }

    post {
        success {
            echo "✅ Deployment completed for ${IMAGE_NAME}:${params.TAG}"
        }
        failure {
            echo "❌ Deployment failed!"
        }
    }
}
