FROM composer:2.1 as composer
FROM node:16 as node
FROM php:8.0-apache

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get install -y --no-install-recommends libzip-dev libpng-dev default-mysql-client supervisor libicu-dev && \
    docker-php-ext-install zip gd pdo pdo_mysql intl && \
    apt-get autoclean -y && \
    rm -rf /var/lib/apt/lists/*

COPY --chown=www-data:www-data . /var/www/html/

WORKDIR /var/www/html/

RUN composer install --optimize-autoloader --no-dev && \
    cp .env.example .env && \
    php artisan key:generate && \
    npm i && npm run production

# Make supervisor log directory
RUN mkdir -p /var/log/supervisor

# Copy local supervisord.conf to the conf.d directory
COPY --chown=root:root ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]`
