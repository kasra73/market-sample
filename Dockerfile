FROM php:7.4-apache AS laravel-builder
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libonig-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    unzip \
    curl

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl gd

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM laravel-builder

ENV APACHE_DOCUMENT_ROOT /var/www/public

# Copy files
COPY . /var/www/

# Set file owner to www-data
RUN chown -R www-data:www-data /var/www

# Set working directory
WORKDIR /var/www

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

RUN composer install --no-dev --no-interaction
