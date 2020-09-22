FROM php:7.4

RUN apt-get update -y && apt-get install -y openssl zip unzip git libxml2-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install  mysqli pdo pdo_mysql tokenizer   xml pcntl

WORKDIR /app
COPY . .
RUN composer install

CMD php artisan serve --host=0.0.0.0
EXPOSE 8000
