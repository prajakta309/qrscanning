FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libzip-dev

# Install PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Create necessary directories
RUN mkdir -p /var/www/html/admin

# Copy application files
COPY ./admin/* /var/www/html/admin/
COPY ./*.php /var/www/html/
COPY ./*.css /var/www/html/
COPY ./*.conf /var/www/html/
COPY ./*.png /var/www/html/
COPY ./*.jpg /var/www/html/

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
COPY apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/admin

# Create start script
RUN echo '#!/bin/bash\n\
apache2-foreground' > /usr/local/bin/start.sh \
    && chmod +x /usr/local/bin/start.sh

# Create index.php that redirects to login.php
RUN echo '<?php header("Location: login.php"); ?>' > /var/www/html/index.php

# Expose port
EXPOSE 8080

# Start script
CMD ["/usr/local/bin/start.sh"] 