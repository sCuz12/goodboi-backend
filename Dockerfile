FROM php:8.0-fpm

#install system dependencies
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
COPY . .

RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev 



# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*s

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd 

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory permissions
COPY --chown=www:www . /var/www


