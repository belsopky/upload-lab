FROM php:8.2-apache
RUN apt-get update && apt-get install -y libsqlite3-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_sqlite
RUN a2enmod rewrite
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf
COPY src/ /var/www/html/
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html/uploads1 /var/www/html/uploads2 \
    /var/www/html/uploads3 /var/www/html/uploads3/avatars \
    /var/www/html/uploads4 /var/www/html/uploads5 \
    /var/www/html/uploads6 /var/www/html/uploads7 \
    /var/www/html/.markers /var/www/html/.uploads
EXPOSE 80
