# syntax=docker/dockerfile:1

############################
# 1) Build frontend (Vite)
############################
FROM node:20-alpine AS nodebuild
WORKDIR /app

# install deps (pakai lockfile kalau ada)
COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi

# build assets
COPY . .
RUN npm run build


############################
# 2) Build PHP deps (Composer)
############################
FROM composer:2 AS composerbuild
WORKDIR /app

# copy dulu composer files biar cache enak
COPY composer.json composer.lock ./

# install vendor TANPA scripts dulu (karena artisan belum ada)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# baru copy source code (ini yang bikin artisan ada)
COPY . .

# sekarang jalankan scripts yang butuh artisan
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

# set DocumentRoot ke /var/www/public
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# copy app + vendor
COPY --from=composerbuild /app /var/www

# copy hasil build vite (manifest.json ada di sini)
COPY --from=nodebuild /app/public/build /var/www/public/build

# permission laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
