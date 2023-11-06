FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    openssl \
    libssl-dev \
    libcurl4-openssl-dev

# Clear apt cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath pdo_mysql

RUN pecl install redis

RUN docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www