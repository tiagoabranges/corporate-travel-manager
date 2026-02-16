FROM php:8.2-fpm-alpine

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

RUN docker-php-ext-install \
  pdo \
  pdo_mysql \
  intl \
  mbstring \
  zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

RUN addgroup -g 1000 app && adduser -G app -g app -s /bin/sh -D app -u 1000 \
  && chown -R app:app /var/www

EXPOSE 8000
