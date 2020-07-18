#!/bin/bash
docker-compose down --rmi all --volumes --remove-orphans
docker-compose build
docker-compose run --rm --no-deps web composer install
docker-compose run --rm --no-deps web composer optimize
docker-compose run --rm --no-deps web php artisan migrate
docker-compose run --rm --no-deps web npm install
docker-compose up -d
