#!/bin/bash

ssh -i "web-ssh-key-private.pem" root@45.124.95.137 "
    cd /var/www/html/ &&
    git clean -df &&
    git reset --hard &&
    git fetch &&
    git pull &&
	chown -R www-data ./storage/ &&
	chown -R www-data ./bootstrap/cache/ &&
    composer install &&
    php artisan config:cache &&
    php artisan migrate --force
"
