FROM php:8.4-fpm

RUN apt-get update

# Install useful tools
RUN apt-get -y install apt-utils nano wget dialog vim

# Install important libraries
RUN echo "\e[1;33mInstall important libraries\e[0m"
RUN apt-get -y install --fix-missing \
    supervisor \
    apt-utils \
    build-essential \
    git \
    curl \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libzip-dev \
    zip \
    nano \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev
    

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Supervisor config
RUN mkdir -p /etc/supervisor/conf.d

COPY supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY supervisor/*.conf /etc/supervisor/conf.d/

WORKDIR /var/www

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]