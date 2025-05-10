#!/bin/bash

echo "🚀 Восстановление Laravel в Docker..."

# 1. Установка зависимостей
echo "📦 Установка composer-зависимостей..."
docker exec -w /app vetclinic-app composer install

# 2. Копирование .env (если нужно)
if [ ! -f backend/.env ]; then
  echo "⚙️  Копирование .env.example -> .env"
  cp backend/.env.example backend/.env
fi

# 3. Генерация ключа
echo "🔐 Генерация application key..."
docker exec -w /app vetclinic-app php artisan key:generate

# 4. Очистка кэша
echo "🧹 Очистка кэша..."
docker exec -w /app vetclinic-app php artisan config:clear
docker exec -w /app vetclinic-app php artisan cache:clear
docker exec -w /app vetclinic-app php artisan route:clear
docker exec -w /app vetclinic-app php artisan view:clear

# 5. Удаление и пересоздание кэш-папок
echo "📂 Пересоздание storage/framework..."
docker exec -w /app vetclinic-app rm -rf storage/framework/views/*
docker exec -w /app vetclinic-app rm -rf storage/framework/cache/*
docker exec -w /app vetclinic-app mkdir -p storage/framework/views storage/framework/cache

# 6. Права доступа
echo "🔓 Выдача прав (chmod + chown)..."
docker exec -w /app vetclinic-app chmod -R 777 storage bootstrap/cache
docker exec -w /app vetclinic-app chown -R www-data:www-data storage bootstrap/cache

# 7. Перезапуск контейнеров
echo "🔁 Перезапуск Docker..."
docker compose restart

echo "✅ Laravel готов! Перейди на http://localhost:8080"
