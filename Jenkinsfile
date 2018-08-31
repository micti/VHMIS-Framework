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
                sh 'vendor/phpunit/phpunit/phpunit --bootstrap bootstrap.php --configuration phpunit.travis.xml --coverage-clover coverage.clover VhmisTest'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
