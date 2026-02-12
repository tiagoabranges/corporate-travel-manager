FROM php:8.2-fpm-alpine

# Dependências do sistema
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    zlib-dev \
    mysql-client

# Extensões PHP necessárias para Laravel + MySQL
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    intl \
    mbstring \
    zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# (Opcional) Permissões para storage/cache
RUN addgroup -g 1000 app && adduser -G app -g app -s /bin/sh -D app -u 1000 \
  && chown -R app:app /var/www

USER app
