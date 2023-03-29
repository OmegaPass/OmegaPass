FROM php:7-fpm

RUN apt-get update && apt-get upgrade -y
RUN apt-get install libxml2-dev -y
RUN docker-php-ext-install mysqli pdo pdo_mysql dom xml && docker-php-ext-enable pdo_mysql
