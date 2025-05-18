# Étape 1 : image PHP officielle avec Apache
FROM php:8.2-apache

# Étape 2 : installation des dépendances système
RUN apt-get update && apt-get install -y \
    git unzip zip curl libicu-dev libpq-dev libonig-dev libzip-dev libxml2-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache

# Étape 3 : activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Étape 4 : installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 5 : définition du dossier de travail
WORKDIR /var/www/html

# Étape 6 : copier les fichiers de l'application
COPY . .

# Étape 7 : installer les dépendances PHP sans scripts auto (évite symfony-cmd)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Étape 8 : définir les permissions correctes
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public

# Étape 9 : configuration Apache pour Symfony (rediriger vers public/index.php)
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Order Allow,Deny\n\
    Allow from All\n\
</Directory>' > /etc/apache2/conf-available/symfony.conf && \
    a2enconf symfony

# Port exposé
EXPOSE 80

# Commande de démarrage par défaut
CMD ["apache2-foreground"]
