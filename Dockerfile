# Utilise PHP 8.2 avec Apache
FROM php:8.2-apache

# Installe les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip opcache

# Active mod_rewrite
RUN a2enmod rewrite

# Définit le répertoire
WORKDIR /var/www/html

# Installe Composer
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer

# Copie le code
COPY . /var/www/html

# Configure Apache pour utiliser /public comme racine
RUN echo "DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Installe les dépendances
RUN composer install --optimize-autoloader --no-dev

# Configure le nom du serveur
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80

CMD ["apache2-foreground"]