# Version de PHP 
FROM php:8.3-apache

# Instalación de  Python 3, pip, MariaDB/MySQL y utilidades del sistema
RUN apt-get update && apt-get install -y \
    python3 \
    python3-pip \
    python3-venv \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Instalamos las extensiones de PHP necesarias para conectarse a la Base de Datos
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitamos mod_rewrite de Apache (necesario para las rutas amigables)
RUN a2enmod rewrite

# Creamos un entorno virtual de Python e instalamos las librerías de TEKO
RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN pip install requests mysql-connector-python

# Indicamos dónde vivirá el código
WORKDIR /var/www/html