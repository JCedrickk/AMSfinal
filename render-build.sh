#!/bin/bash

echo "🚀 Starting Render build process..."

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

# Install NPM dependencies and build assets
echo "📦 Installing NPM dependencies..."
npm install --legacy-peer-deps

echo "🔨 Building assets..."
npm run build

# Run database migrations (CRITICAL!)
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed courses data
echo "🌱 Seeding courses data..."
php artisan db:seed --class=CourseSeeder --force

# Clear and cache config for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"