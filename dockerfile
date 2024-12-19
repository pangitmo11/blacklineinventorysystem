# Use the official PHP image with FPM (FastCGI Process Manager)
FROM php:8.1-fpm

# Install dependencies for Laravel (PHP extensions, Git, and Composer)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/html inside the container
WORKDIR /var/www/html

# Copy all the files from your local project into the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader

# Expose port 80 for the application
EXPOSE 80

# Start the Laravel development server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
