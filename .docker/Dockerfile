FROM php:8.1-apache

RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN apt-get update && apt-get upgrade -y && apt-get install -y git && apt-get install vim -y

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN /usr/sbin/a2enmod rewrite

#RUN sudo a2enmod rewrite

RUN apt-get install -y libpq-dev

#RUN docker-php-ext-install pdo_pgsql

COPY default.conf /etc/apache2/sites-available/000-default.conf