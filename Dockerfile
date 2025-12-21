FROM php:8.2-apache

# Install dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev curl \
  && docker-php-ext-install pdo pdo_mysql zip \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (biar cache layer kepake)
COPY composer.json composer.lock ./

# Install vendor TANPA menjalankan composer scripts (karena artisan belum dicopy)
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts

# Copy seluruh source aplikasi (termasuk artisan)
COPY . .

# Pastikan folder cache ada dan permission aman
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache || true

# Set Apache DocumentRoot ke /public
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["apache2-foreground"]