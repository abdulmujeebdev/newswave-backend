# Use the official PHP image as the base image
FROM php:8.1-fpm-alpine

# Set the working directory
WORKDIR /var/www

# Install dependencies
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    gd \
    zip \
    intl \
    exif

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy Laravel files to the container
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel directories
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
