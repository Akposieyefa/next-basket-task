FROM php:8.2-fpm
ARG uid=1000
ARG user=default_user
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
RUN node --version
RUN npm --version
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
WORKDIR /var/www
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-autoloader --no-scripts
COPY package.json package-lock.json ./

RUN npm install
COPY . .

RUN echo "User: $user" && id "$user"
RUN composer clear-cache
RUN composer dump-autoload --optimize && \
    chown -R $user:$user ./
RUN chown -R www-data:www-data storage bootstrap/cache
COPY startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh
ENTRYPOINT ["/usr/local/bin/startup.sh"]
CMD ["php-fpm"]