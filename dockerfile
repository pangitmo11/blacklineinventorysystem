# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www

# Install dependencies (e.g., Composer, Node.js)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the Laravel app files to the container
COPY . .

# Install Laravel's PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Expose port 9000 to the outside world
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
