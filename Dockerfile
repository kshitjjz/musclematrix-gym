FROM php:8.2-apache

# Disable conflicting MPM modules
RUN a2dismod mpm_event || true
RUN a2dismod mpm_worker || true
RUN a2enmod mpm_prefork

# Enable PHP extensions
RUN docker-php-ext-install mysqli

# Copy project files
COPY . /var/www/html/

EXPOSE 80
