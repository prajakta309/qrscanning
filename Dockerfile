# Use the official PHP image with Apache
FROM php:8.1-apache

# Install required extensions for MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Set working directory inside the container
WORKDIR /var/www/html

# Copy project files to the container
COPY . /var/www/html/

# Set Apache ServerName to fix warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Change ownership and permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port 80 for web traffic
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
