# Utilisez une image officielle PHP 8.2 avec Apache
FROM php:8.2-apache

# Installez les extensions nécessaires à Symfony
RUN docker-php-ext-install pdo_mysql

# Copiez votre code dans le conteneur
COPY . /var/www/html

# Installez Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installez les dépendances
RUN cd /var/www/html && composer install --optimize-autoloader --no-dev

# Configurez Apache pour servir le dossier public
RUN a2enmod rewrite
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Exposez le port 80 (Render utilisera $PORT)
EXPOSE 80

# Démarrez Apache
CMD ["apache2-foreground"]