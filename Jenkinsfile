pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building..'
            }
        }
        stage('Test') {
            steps {
                sh 'wget -O phpunit https://phar.phpunit.de/phpunit-7.phar'
                sh 'chmod +x phpunit'
                sh './phpunit --bootstrap bootstrap.php --configuration phpunit.travis.xml --coverage-clover coverage.clover VhmisTest'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
