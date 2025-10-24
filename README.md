🎓 University System

Повнофункціональна система для управління університетом, створена з використанням Laravel, Vue 3, PostgreSQL, Redis та Docker.
Мета проєкту — спростити адміністрування навчального процесу, роботу з базами даних і забезпечити сучасний веб-інтерфейс.

⚙️ Технологічний стек
Компонент	Технологія
Backend	PHP 8.2, Laravel 11
Frontend	Vue 3, Vite
База даних	PostgreSQL 15
Кеш/Сесії/Черги	Redis
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
└── docker-compose.yml          # Опис усіх сервісів

🚀 Запуск проєкту
1. Клонування репозиторію
   git clone https://github.com/AppRangejn/universitysystem.git
   cd universitysystem

2. Створення .env для Laravel
   cp src/backend/.env.example src/backend/.env


Перевір, щоб у .env були такі налаштування:

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=university
DB_USERNAME=laravel
DB_PASSWORD=secret

3. Запуск Docker контейнерів
   docker compose up -d --build


🧩 Контейнери, що запустяться:

university-backend — Laravel (php-fpm)

university-frontend — Vue (Node.js)

university-nginx — вебсервер

university-db — PostgreSQL

university-redis — Redis

4. Налаштування Laravel
   docker exec -it university-backend bash
   composer install
   php artisan key:generate
   php artisan migrate
   exit

🌐 Доступ до додатку
Сервіс	URL
Web (Frontend + Backend)	http://localhost:8081

PostgreSQL	localhost:5432
Redis	localhost:6379
🧰 Корисні команди
Команда	Опис
docker compose build	Перезібрати контейнери
docker compose down	Зупинити проєкт
docker compose down --volumes	Повністю очистити БД і кеш
docker exec -it university-backend bash	Увійти в контейнер Laravel
php artisan migrate	Прогнати міграції
php artisan tinker	Консоль Laravel
📦 Логіка роботи

Laravel (backend) обробляє API-запити та підключається до PostgreSQL і Redis.

Vue (frontend) компілюється через Vite і віддається Nginx.

Docker Compose автоматично з'єднує всі сервіси у єдину мережу university.

👨‍💻 Автор
Кіріл, Розробник backend (Laravel) із фокусом на структурні, практичні та масштабовані рішення.

🏁 Ліцензія
Використання дозволено лише в навчальних або дослідницьких цілях.
© 2025, University System