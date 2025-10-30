# -------------------------------------------------------
# Laravel 10 / ORPP Deployment on Render with PHP 8.2 + Apache
# -------------------------------------------------------

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

# 6️⃣ Ensure Laravel directories exist & set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache

# 7️⃣ Configure Apache to use Laravel public folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# 8️⃣ Enable .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 9️⃣ Expose port 80
EXPOSE 80

# 1️⃣0️⃣ Start Apache in foreground
CMD ["apache2-foreground"]
