FROM amazonlinux:2

RUN amazon-linux-extras enable php7.4 epel
RUN yum clean metadata
RUN yum install -y epel-release zip unzip wget curl

RUN yum install -y httpd
ADD ./envfiles/httpd/conf.d/ /etc/httpd/conf.d/

RUN yum install -y php php-bcmath php-ctype php-fileinfo php-gd php-json php-mbstring php-mysqli php-openssl php-pdo php-pgsql php-tokenizer php-xml
ADD ./envfiles/php.ini /etc/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://rpm.nodesource.com/setup_12.x | bash
RUN yum install -y nodejs

RUN yum install -y supervisor
ADD ./envfiles/supervisor.d/ /etc/supervisor.d/

ADD ./envfiles/ffmpeg/ /usr/local/bin/ffmpeg/
RUN ln -s /usr/local/bin/ffmpeg/ffmpeg /usr/bin/ffmpeg

WORKDIR /var/www/html/

ADD ./envfiles/init.sh /tmp/init.sh
RUN chmod +x /tmp/init.sh
CMD ["/tmp/init.sh"]
