FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    libcurl4-openssl-dev

RUN docker-php-ext-install \
    bcmath \
    intl \
    mbstring \
    pdo \
    pdo_pgsql \
    pgsql \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=8000