# syntax=docker/dockerfile:1

############################
# 1) Build frontend (Vite)
############################
FROM node:20-bookworm-slim AS nodebuild
WORKDIR /app

# (opsional tapi membantu) upgrade npm biar bug optional deps makin kecil kemungkinan
RUN npm i -g npm@11

# install deps (wajib copy lockfile juga)
COPY package.json package-lock.json ./
RUN npm ci --include=optional

# build assets
COPY . .
RUN npm run build


############################
# 2) Build PHP deps (Composer)
############################
FROM composer:2 AS composerbuild
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

COPY . .
RUN composer dump-autoload --optimize \
 && php artisan package:discover --ansi


############################
# 3) Runtime image (Apache)
############################
FROM php:8.2-apache
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
 && docker-php-ext-install pdo pdo_mysql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# app + vendor
COPY --from=composerbuild /app /var/www

# vite build output (manifest.json)
COPY --from=nodebuild /app/public/build /var/www/public/build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
