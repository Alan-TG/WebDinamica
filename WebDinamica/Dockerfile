# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala las extensiones necesarias (por ejemplo, mysqli para MySQL)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia todos los archivos del proyecto al directorio raíz del servidor web
COPY WebDinamica/ /var/www/html/

# Da permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80 (usado por Apache)
EXPOSE 80