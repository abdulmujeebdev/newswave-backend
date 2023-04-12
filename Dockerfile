# Use the official PHP image as the base image
FROM php:8.1

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the entire Laravel backend project to the container
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --no-ansi --no-scripts --no-progress --no-suggest

# Pull MySQL 8.0 image
FROM mysql:8.0

# Expose port 8000 for the Laravel app to run
EXPOSE ${PORT}

# Start the Laravel app
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
