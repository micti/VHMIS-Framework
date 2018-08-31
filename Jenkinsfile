node {
    checkout scm 
}

pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                echo 'Building..'
            }
        }
        stage('Test') {
            sh 'vendor/phpunit/phpunit/phpunit --bootstrap bootstrap.php --configuration phpunit.travis.xml --coverage-clover coverage.clover VhmisTest'
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
