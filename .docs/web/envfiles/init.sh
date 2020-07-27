#!/bin/bash

cd /var/www/html/

chown -R apache bootstrap/cache/
chown -R apache storage/

/usr/sbin/httpd -DFOREGROUND
/usr/sbin/supervisord -DFOREGROUND
