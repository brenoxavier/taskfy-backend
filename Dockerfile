FROM nginx:1.21

ENV RODA=rodinha

USER root

ENV RODA=rodovia

COPY ./deploy/nginx/templates/default.conf.template /etc/nginx/nginx.conf

RUN apt-get update && apt-get install -y \
    php7.4-fpm \
    php-curl \
    php-xml \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/php

WORKDIR /var/www/php

RUN composer install

EXPOSE 8080

CMD chmod 755 /deploy/*

CMD ["./deploy/start.sh"]
