# ubuntu 22.04 이용
FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get update && apt-get install -y software-properties-common
RUN apt-get install -y git

# Nginx install
# RUN apt-get install -y nginx
# COPY default /etc/nginx/sites-enabled/default

# PHP Setting
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update && apt-get install -y php8.2
RUN apt-get install -y php8.2-mbstring php8.2-mysql php8.2-pdo php8.2-curl php8.2-xml php8.2-fpm
RUN apt-get install -y composer
RUN apt-get install -y php-pear
RUN apt-get install -y php-dev
RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"`
RUN apt-get install -y php-mongodb

# git clone repository
WORKDIR /app
RUN git clone https://github.com/hyeokjonghan/arkinfo_server.git

WORKDIR /app/arkinfo_server
RUN chown -R www-data:www-data bootstrap/cache
RUN chown -R www-data:www-data storage

# mysql setting
RUN apt-get install -y mysql-server

# mongodb setting
RUN apt-get install -y wget
RUN wget -qO - https://www.mongodb.org/static/pgp/server-6.0.asc | apt-key add -
RUN echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu jammy/mongodb-org/6.0 multiverse" | tee /etc/apt/sources.list.d/mongodb-org-6.0.list
RUN apt-get update
RUN apt-get install -y mongodb-org

EXPOSE 80

CMD ["tail", "-f", "/dev/null"]