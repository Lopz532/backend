FROM php:8.2-apache 
COPY . C:\Users\kenet\OneDrive\Desktop\backend\public
RUN a2enmod rewrite
RUN sed -i 's!/var/www/html!/var/www/html/public!g'\
    /etc/apache2/sites-available/000-default.conf
EXPOSE 80