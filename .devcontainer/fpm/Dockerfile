# From official php image.
FROM php:8.0-fpm
# Install dependencies.
RUN apt-get update && apt-get install -y --no-install-recommends \
    default-libmysqlclient-dev \
    libzip-dev \
    && mkdir -p /usr/src/php/ext/redis \
    && curl -fsSL --ipv4 https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C "/usr/src/php/ext/redis" --strip 1 \
    && docker-php-ext-install pdo_mysql zip redis
