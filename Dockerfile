# syntax=docker/dockerfile:1

############################
# 1) Frontend build (Vite)
############################
FROM node:20-bookworm-slim AS nodebuild
WORKDIR /app

COPY package*.json ./
RUN npm ci --no-audit --no-fund || npm install --no-audit --no-fund

COPY . .
RUN npm run build


############################
# 2) PHP deps (Composer)
############################
FROM composer:2 AS composerbuild
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

COPY . .
RUN composer dump-autoload --optimize


############################
# 3) Runtime (Apache + PHP)
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

# AllowOverride All (penting untuk Laravel routing via .htaccess)
RUN sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

COPY --from=composerbuild /app /var/www
COPY --from=nodebuild /app/public/build /var/www/public/build

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80
CMD ["apache2-foreground"]
