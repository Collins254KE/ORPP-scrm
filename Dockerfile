# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Enable commonly used PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy all project files into the web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
