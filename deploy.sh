#!/bin/bash

ssh -i "web-ssh-key-private.pem" root@45.124.95.137 "
    cd /var/www/html/ &&
    git clean -df &&
    git reset --hard &&
    git fetch &&
    git pull &&
	chown -R apache ./storage/ &&
	chown -R apache ./bootstrap/cache/ &&
    composer install &&
    php artisan config:cache &&
    php artisan migrate --force
"
