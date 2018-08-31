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
                sh '/usr/local/bin/phpunit --bootstrap bootstrap.php --configuration phpunit.travis.xml --coverage-clover coverage.clover VhmisTest'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
