# 1️⃣ Use official PHP 8.2 image with Apache
FROM php:8.2-apache

# 2️⃣ Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite headers

# 3️⃣ Set Apache ServerName to suppress warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 4️⃣ Copy project files into container
COPY . /var/www/html

# 5️⃣ Set working directory
WORKDIR /var/www/html

# 6️⃣ Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 7️⃣ Ensure .htaccess overrides are enabled
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 8️⃣ Expose port 80
EXPOSE 80

# 9️⃣ Start Apache in foreground
CMD ["apache2-foreground"]
