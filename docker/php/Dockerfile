FROM php:8.2-fpm

# 🧰 Встановлення системних бібліотек і залежностей
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev libpq-dev libssl-dev pkg-config \
    && docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 🧩 Встановлення PHP-розширення Redis (через PECL)
RUN pecl channel-update pecl.php.net \
    && printf "\n" | pecl install redis \
    && docker-php-ext-enable redis

# 📦 Composer з офіційного образу
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# 📂 Робоча директорія
WORKDIR /var/www/html

# 👨‍🔧 Права доступу для Laravel
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ⚡️ Опціонально: встанови час у контейнері (щоб не було зсуву в логах)
ENV TZ=Europe/Kyiv

# 🚀 Запуск PHP-FPM
CMD ["php-fpm"]
