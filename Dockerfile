FROM php:8.2-fpm

# Установка системных зависимостей
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    npm \
    libzip-dev \
    zip \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev

# Конфигурирование и установка PHP расширений
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd mbstring pdo pdo_pgsql bcmath xml zip sockets pcntl intl

# Установка расширения Redis
RUN pecl install redis && docker-php-ext-enable redis

# Очистка кэша APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка Node.js и npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Настройка рабочей директории
WORKDIR /var/www/html

# Копирование файлов проекта
COPY . .

# Установка зависимостей Laravel и компиляция ассетов
RUN composer install --prefer-dist --no-scripts --no-dev && \
    composer dump-autoload --optimize

# Выполнение команд Artisan для оптимизации
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Установка прав
RUN chown -R www-data:www-data storage bootstrap/cache

# Открытие порта
EXPOSE 8000

# Запуск контейнера
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]