#!/bin/bash
docker-compose down --rmi all --volumes --remove-orphans
docker-compose build
docker-compose up -d
docker-compose run --rm --no-deps web rm -rf ./vendor/ckfinder/
docker-compose run --rm --no-deps web composer install
docker-compose run --rm --no-deps web php artisan ckfinder:download
docker-compose run --rm --no-deps web php artisan ckfinder:enable_custom_endpoint
docker-compose run --rm --no-deps web php artisan optimize
docker-compose run --rm --no-deps web php artisan migrate
docker-compose run --rm --no-deps web npm install
echo -e "\e[32mDocker build completed!"
