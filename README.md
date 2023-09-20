# Plentific - user API

## Build docker image
`docker build -t plentific-user-api .`

## Run docker image
`docker run -t -d -p 80:8080 -d -v $(pwd):/var/www/html plentific-user-api`

## Run commands inside the container
`docker exec -it <container_id> bash`

## Install required dependencies
`docker exec <container_id> composer install`

## Run unit tests
`docker exec <container_id> composer tests:unit`

## Run phpstan
`docker exec <container_id> composer phpstan`

## Run phpcs
`docker exec <container_id> composer phpcs`
