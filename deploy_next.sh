#!/bin/bash

ssh -i web-ssh-key-private.pem root@103.56.158.35 <<EOF
cd /var/www/tomato
git reset --hard
git pull
composer install
npm install
php artisan config:cache
php artisan migrate
EOF
