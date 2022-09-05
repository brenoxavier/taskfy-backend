FROM nginx:1.21.6

WORKDIR /var/www/php

RUN apt-get update && \
    apt-get install -y \
    php7.4-fpm \
    php-curl \
    php-xml \
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

COPY nginx.conf /etc/nginx/nginx.conf

COPY entrypoint.sh /entrypoint.sh

COPY . .

RUN chmod +x /entrypoint.sh

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 8080

ENTRYPOINT [ "/entrypoint.sh" ]
