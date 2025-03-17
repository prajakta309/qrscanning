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

RUN echo "<Directory /var/www/html/> \
    Options -Indexes +FollowSymLinks \
    AllowOverride All \
    Require all granted \
</Directory>" > /etc/apache2/conf-available/custom.conf \
&& a2enconf custom
