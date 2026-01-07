# Utilise l'image officielle PHP 8.2 avec Apache (intl déjà inclus)
FROM php:8.2-apache

# Installe les outils système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip opcache

# Active mod_rewrite pour Symfony
RUN a2enmod rewrite

# Définit le répertoire de travail
WORKDIR /var/www/html

# Installe Composer proprement (version stable)
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

# Copie les fichiers du projet
COPY . /var/www/html

# Installe les dépendances en production
RUN composer install --optimize-autoloader --no-dev

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose le port 80 (Render utilisera $PORT)
EXPOSE 80

# Lance Apache
CMD ["apache2-foreground"]