pipeline {
    agent any

    environment {
        GIT_REPO_URL = "https://github.com/Sothi805/social-media-app.git"
        IMAGE_NAME = "vethsothi/social-media-app"
        DOCKER_CREDENTIALS = "dockerhub_creds"
        REMOTE_SSH_KEY = "REMOTE_SSH_KEY"
        REMOTE_USER = "ubuntu"
        REMOTE_HOST = "98.81.79.6"
        REMOTE_PATH = "/home/ubuntu/deploy"
    }

    parameters {
        gitParameter(
            name: 'TAG',
            type: 'PT_TAG',
            defaultValue: '',
            description: 'Select Git tag to build',
            useRepository: 'https://github.com/Sothi805/social-media-app.git',
            sortMode: 'DESCENDING_SMART'
        )
        gitParameter(
            name: 'BRANCH',
            type: 'PT_BRANCH',
            defaultValue: 'main',
            description: 'Select branch if no tag',
            useRepository: 'https://github.com/Sothi805/social-media-app.git',
            sortMode: 'ASCENDING_SMART'
        )
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    if (params.TAG) {
                        echo "📦 Checking out tag: ${params.TAG}"
                        checkout([$class: 'GitSCM',
                            branches: [[name: "refs/tags/${params.TAG}"]],
                            userRemoteConfigs: [[url: env.GIT_REPO_URL]]
                        ])
                    } else {
                        echo "📦 Checking out branch: ${params.BRANCH}"
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
                    if (isUnix()) {
                        sh "docker build -t ${IMAGE_NAME}:${params.TAG} ."
                    } else {
                        bat "docker build -t ${IMAGE_NAME}:${params.TAG} ."
                    }
                    echo "✅ Built image ${IMAGE_NAME}:${params.TAG}"
                }
            }
        }

        stage('Login to Docker Hub') {
            steps {
                withCredentials([usernamePassword(credentialsId: "${DOCKER_CREDENTIALS}", usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    script {
                        if (isUnix()) {
                            sh 'echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin'
                        } else {
                            bat 'echo %DOCKER_PASS% | docker login -u %DOCKER_USER% --password-stdin'
                        }
                    }
                }
            }
        }

        stage('Push image to Docker Hub') {
            steps {
                script {
                    if (isUnix()) {
                        sh """
                            docker push ${IMAGE_NAME}:${params.TAG}
                            docker tag ${IMAGE_NAME}:${params.TAG} ${IMAGE_NAME}:latest
                            docker push ${IMAGE_NAME}:latest
                        """
                    } else {
                        bat """
                            docker push ${IMAGE_NAME}:${params.TAG}
                            docker tag ${IMAGE_NAME}:${params.TAG} ${IMAGE_NAME}:latest
                            docker push ${IMAGE_NAME}:latest
                        """
                    }
                    echo "📤 Pushed ${IMAGE_NAME}:${params.TAG} and :latest to Docker Hub"
                }
            }
        }

        stage('Deploy to EC2') {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: "${REMOTE_SSH_KEY}", keyFileVariable: 'SSH_KEY')]) {
                    script {
                        if (isUnix()) {
                            sh """
                                echo "🚀 Copying project to EC2..."
                                scp -i $SSH_KEY -o StrictHostKeyChecking=no -r * ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}/

                                echo "⚙️ Building and running on EC2..."
                                ssh -i $SSH_KEY -o StrictHostKeyChecking=no ${REMOTE_USER}@${REMOTE_HOST} '
                                    cd ${REMOTE_PATH}
                                    sudo apt-get update -y
                                    sudo apt-get install -y docker-compose-plugin

                                    # 🧾 Ensure .env exists
                                    if [ ! -f .env ]; then
                                        echo "Creating .env file..."
                                        cp .env.example .env || touch .env
                                    fi

                                    echo "🧱 Rebuilding Docker containers..."
                                    sudo docker compose down || true
                                    sudo docker compose build --no-cache
                                    sudo docker compose up -d
                                '
                            """
                        } else {
                            bat """
                                pscp -i %SSH_KEY% -batch -r * ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}\\
                                plink -i %SSH_KEY% ${REMOTE_USER}@${REMOTE_HOST} "cd ${REMOTE_PATH} && if not exist .env copy .env.example .env && sudo docker compose build --no-cache && sudo docker compose up -d"
                            """
                        }
                    }
                }
            }
        }
    }

    post {
        success {
            echo "✅ Deployment completed successfully for ${IMAGE_NAME}:${params.TAG}"
        }
        failure {
            echo "❌ Deployment failed. Please check the logs for details."
        }
    }
}
