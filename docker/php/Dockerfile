FROM php:8.2-fpm

# 🧰 Встановлення системних залежностей і PHP-розширень
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev libpq-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 📦 Копіюємо Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 📂 Робоча директорія
WORKDIR /var/www/html

# 👨‍🔧 Права доступу
RUN chown -R www-data:www-data /var/www/html

# 🚀 Запуск PHP-FPM
CMD ["php-fpm"]
