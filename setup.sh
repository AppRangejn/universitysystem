#!/bin/bash
set -e

echo "🚀 Запуск проекту UniversitySystem..."

if [ ! -f src/backend/.env ]; then
    echo "📋 Створюю .env файл..."
    cp src/backend/.env.example src/backend/.env
fi

echo "🐳 Будую Docker контейнери..."
docker compose up -d --build

echo "🔑 Генерую Laravel APP_KEY..."
docker compose exec backend php artisan key:generate

echo "🧱 Виконую міграції..."
docker compose exec backend php artisan migrate --seed

echo "🔗 Створюю символічне посилання для storage..."
docker compose exec backend php artisan storage:link

echo "✅ Готово! Проєкт доступний за адресою: http://localhost:8081"
