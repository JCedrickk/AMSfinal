#!/bin/bash

echo "🚀 Starting Render build process..."

# Exit on error
set -e

# Install PHP dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

# Install NPM dependencies and build assets
echo "📦 Installing NPM dependencies..."
npm install --legacy-peer-deps

echo "🔨 Building assets..."
npm run build

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed courses specifically
echo "🌱 Seeding courses..."
php artisan db:seed --class=CourseSeeder --force

# Run your specific admin seeder
echo "🌱 Creating admin user..."
php artisan db:seed --class=AdminUserSeeder --force

# Then run other seeders if needed
php artisan db:seed --force

# Cache for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Build completed successfully!"