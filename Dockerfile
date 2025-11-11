FROM php:8.2-apache

# Install dependencies including PostgreSQL extension
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql \
    && docker-php-ext-install mbstring pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy project files
COPY . /var/www/html
WORKDIR /var/www/html

# Fix permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
