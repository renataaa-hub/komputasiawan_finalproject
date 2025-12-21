FROM php:8.2-apache

# Install dependency Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    curl \
 && docker-php-ext-install pdo pdo_mysql zip \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# Install Composer (resmi & cepat)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory Laravel
WORKDIR /var/www

# Copy composer file dulu (biar cache optimal)
COPY composer.json composer.lock ./

# INI KUNCI UTAMA → bikin folder vendor/
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copy seluruh source code
COPY . .

# Permission Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

# Set document root ke /public
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

EXPOSE 80
