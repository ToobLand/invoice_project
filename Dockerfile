# Gebruik de PHP 8 Apache image
FROM php:8.0-apache

# Installeer Composer
RUN apt-get update && \
    apt-get install -y curl git unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Stel de document root in op de public directory van het project
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Verplaats de configuratie van Apache naar de juiste document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Schakel Apache mod_rewrite in, indien nodig
RUN a2enmod rewrite

# Kopieer de projectbestanden naar de container
COPY . /var/www/html

WORKDIR /var/www/html