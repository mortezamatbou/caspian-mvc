FROM php:8.2-apache AS builder

ARG USER_ID

ARG GROUP_ID
RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data && \
    if getent group www-data ; then groupdel www-data; fi && \
    groupadd -g ${GROUP_ID} www-data && \
    useradd -l -u ${USER_ID} -g www-data www-data && \
    install -d -m 0755 -o www-data -g www-data /home/www-data && \
    chown -R www-data:www-data /var/www/html && \
    chown -R www-data:www-data /var/lib/apache2 && \
    chown -R www-data:www-data /var/log/apache2 && \
    chown -R www-data:www-data /var/run/apache2 && \
    chown -R www-data:www-data /etc/apache2; \
fi

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libxml2-dev \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

RUN pecl install -o -f redis
RUN apt-get update
RUN docker-php-ext-install mysqli pdo pdo_mysql xml zip
RUN docker-php-ext-enable pdo_mysql xml redis xml zip

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer


RUN a2enmod rewrite && service apache2 restart

USER www-data
