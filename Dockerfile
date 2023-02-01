FROM php:7.4-cli

RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

#VOLUME . .

#EXPOSE 8000

#CMD [ "php", "-S", "0.0.0.0:8000" ]