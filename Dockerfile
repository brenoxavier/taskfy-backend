FROM nginx:1.21.6

COPY nginx.conf /etc/nginx/nginx.conf

COPY entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

RUN apt-get update && \
    apt-get install -y \
    php7.4-fpm \
    php-curl \
    php-xml \
    php-gd \
    php-pgsql \
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

WORKDIR /var/www/php

COPY . .

EXPOSE 8080

ENTRYPOINT [ "/entrypoint.sh" ]
