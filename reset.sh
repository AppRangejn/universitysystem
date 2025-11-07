#!/bin/bash
set -e

echo "♻️ Повний ресет середовища..."

# 1️⃣ Зупиняємо все і видаляємо volume
docker compose down -v

# 2️⃣ Перезапускаємо чисто
docker compose up -d --build

# 3️⃣ Міграції + сідери
docker compose exec backend php artisan migrate:fresh --seed

# 4️⃣ Лінкуємо storage
docker compose exec backend php artisan storage:link

echo "✅ Повне оновлення завершено! Перевір: http://localhost:8081"
