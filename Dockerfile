# Étape 1 : image PHP officielle avec Apache
FROM php:8.2-apache

# Étape 2 : installe les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libonig-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache \
    && a2enmod rewrite

# Étape 3 : installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Étape 4 : copie le projet
COPY . /var/www/html/

# Étape 5 : configuration Apache/Symfony
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# Symfony stocke ses fichiers publics ici
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Corrige les permissions si nécessaire
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

EXPOSE 80
