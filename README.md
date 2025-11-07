🎓 University System

University System — це повнофункціональна система для управління університетом, побудована на сучасному стеку: Laravel, Vue 3, PostgreSQL, Redis та Docker.
Мета проєкту — спростити адміністрування навчального процесу, управління базами даних і надати зручний веб-інтерфейс для користувачів.

⚙️ Технологічний стек
Компонент	Технологія
Backend	PHP 8.2, Laravel 11
Frontend	Vue 3, Vite
База даних	PostgreSQL 15
Кеш / Сесії / Черги	Redis
Контейнеризація	Docker, Docker Compose
Вебсервер	Nginx
🧱 Структура проєкту
universitysystem/
├── docker/
│   ├── nginx/
│   │   └── default.conf        # Конфігурація Nginx
│   └── php/
│       └── Dockerfile          # PHP + Composer + Redis розширення
│
├── src/
│   ├── backend/                # Laravel-додаток
│   └── frontend/               # Vue 3 застосунок
│
├── docker-compose.yml          # Docker конфігурація
├── setup.sh                    # Автоматичний запуск
├── stop.sh                     # Зупинка контейнерів
├── reset.sh                    # Повне пересоздання проєкту
└── README.md

🚀 Запуск проєкту
1️⃣ Клонування репозиторію
git clone https://github.com/AppRangejn/universitysystem.git
cd universitysystem

2️⃣ Створення .env для Laravel
cp src/backend/.env.example src/backend/.env


Перевір, щоб у .env були такі налаштування:

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=university
DB_USERNAME=laravel
DB_PASSWORD=secret

3️⃣ Запуск контейнерів
./setup.sh


Цей скрипт:

створює .env, якщо його немає;

збирає контейнери Docker;

генерує ключ застосунку;

виконує міграції та сидинг тестових даних;

створює storage:link.

🌐 Доступ до додатку
Сервіс	URL
Web (Frontend + Backend API)	http://localhost:8081

PostgreSQL	localhost:5432
Redis	localhost:6379
🧰 Корисні команди
Команда	Опис
docker compose build	Перезібрати контейнери
docker compose down	Зупинити проєкт
docker compose down -v	Повністю очистити БД і кеш
docker exec -it university-backend bash	Увійти в контейнер Laravel
php artisan migrate	Прогнати міграції
php artisan tinker	Консоль Laravel
🧩 Конфігураційні файли
🐳 docker-compose.yml
services:
backend:
build:
context: ./docker/php
container_name: university-backend
volumes:
- ./src/backend:/var/www/html
depends_on:
- db
- redis
networks:
- university
environment:
- APP_ENV=local
- APP_DEBUG=true
- APP_URL=http://localhost:8081

frontend:
image: node:20
container_name: university-frontend
working_dir: /app
volumes:
- ./src/frontend:/app
- /app/node_modules
ports:
- "5173:5173"
command: sh -c "npm install && npm run dev -- --host"
networks:
- university

web:
image: nginx:latest
container_name: university-nginx
ports:
- "8081:80"
volumes:
- ./src/backend:/var/www/html
- ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
depends_on:
- backend
- frontend
networks:
- university

db:
image: postgres:15-alpine
container_name: university-db
environment:
POSTGRES_DB: university
POSTGRES_USER: laravel
POSTGRES_PASSWORD: secret
volumes:
- pgdata:/var/lib/postgresql/data
ports:
- "5432:5432"
networks:
- university

redis:
image: redis:alpine
container_name: university-redis
ports:
- "6379:6379"
networks:
- university

volumes:
pgdata:

networks:
university:

🧱 docker/php/Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev libpq-dev libssl-dev pkg-config \
&& docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd zip \
&& apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl channel-update pecl.php.net \
&& printf "\n" | pecl install redis \
&& docker-php-ext-enable redis

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
RUN mkdir -p /var/www/html/public/photos /var/www/html/storage /var/www/html/bootstrap/cache \
&& chown -R www-data:www-data /var/www/html \
&& chmod -R 775 /var/www/html/public /var/www/html/storage /var/www/html/bootstrap/cache

ENV TZ=Europe/Kyiv
CMD ["php-fpm"]

🌐 docker/nginx/default.conf
server {
listen 80;
server_name localhost;

    root /var/www/html/public;
    index index.php index.html;

    location ^~ /api/ {
        rewrite ^/api/?(.*)$ /index.php?$1 last;
        include fastcgi_params;
        fastcgi_pass university-backend:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root/index.php;
    }

    location /storage/ {
        alias /var/www/html/storage/app/public/;
        access_log off;
        expires max;
    }

    location ~ ^/(login|register|logout|user)$ {
        return 404;
    }

    location / {
        proxy_pass http://university-frontend:5173;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass university-backend:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}

🧩 Скрипти автоматизації
🛠 setup.sh
#!/bin/bash
set -e

echo "🚀 Запуск проекту UniversitySystem..."

if [ ! -f src/backend/.env ]; then
echo "📋 Створюю .env файл..."
cp src/backend/.env.example src/backend/.env
fi

docker compose up -d --build
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate:fresh --seed
docker compose exec backend php artisan storage:link

echo "✅ Готово! Відкрий: http://localhost:8081"

⏹ stop.sh
#!/bin/bash
set -e
docker compose down
echo "✅ Контейнери зупинено."

♻️ reset.sh
#!/bin/bash
set -e
docker compose down -v
docker compose up -d --build
docker compose exec backend php artisan migrate:fresh --seed
docker compose exec backend php artisan storage:link
echo "♻️ Система повністю пересобрана!"

👨‍💻 Автор

Кіріл — студент-розробник (Laravel backend), який цінує порядок у коді, практичні рішення та чисту архітектуру.

🏁 Ліцензія

Використання дозволено лише в навчальних або дослідницьких цілях.
© 2025, University System