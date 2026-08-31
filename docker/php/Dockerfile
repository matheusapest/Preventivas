FROM php:8.3-fpm

LABEL maintainer="Matheus Apestegui"

ARG UID=1000
ARG GID=1000

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    nano \
    vim \
    procps \
    iputils-ping \
    net-tools \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libsqlite3-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Ajusta UID/GID do www-data para coincidir com o usuário do host
RUN groupmod -g ${GID} www-data \
    && usermod -u ${UID} -g ${GID} www-data

# GD
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Extensões PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mysqli \
    mbstring \
    intl \
    zip \
    gd \
    exif \
    bcmath \
    pcntl

# Redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html

USER www-data

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
