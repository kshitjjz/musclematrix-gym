FROM php:8.1-apache

# Install mysqli
RUN docker-php-ext-install mysqli

# Enable Apache rewrite (optional but useful)
RUN a2enmod rewrite

# Copy project
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
