# From official php image.
FROM php:8.0-cli
# Install Composer from: https://hub.docker.com/_/composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
# Install dependencies.
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && groupadd --gid 1000 vscode \
    && useradd -s /bin/bash --uid 1000 --gid 1000 -m vscode \
    && apt-get install -y sudo \
    && echo vscode ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/vscode \
    && chmod 0440 /etc/sudoers.d/vscode \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    default-mysql-client \
    libzip-dev \
    libpng-dev \
    gnupg \
    nodejs \
    poppler-utils \
    && mkdir -p /usr/src/php/ext/redis \
    && curl -fsSL --ipv4 https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C "/usr/src/php/ext/redis" --strip 1 \
    && docker-php-ext-install pdo_mysql zip redis gd \
    && apt -y autoremove \
    && apt clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Prevent container from exiting early.
CMD ["sleep", "infinity"]
