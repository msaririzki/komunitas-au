#!/bin/sh

cd /var/www

# 1. Environment Setup
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    php artisan key:generate
fi

# 2. PHP Dependencies
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# 3. Node Dependencies & Build
if [ ! -d "node_modules" ] || [ ! -d "public/build" ]; then
    echo "Installing Node dependencies and building assets..."
    npm install
    npm run build
fi

# 4. Wait for Database (Simple sleep, usually DB container takes a few seconds)
echo "Waiting for Database..."
sleep 10

# 5. Database Migration
echo "Running Migrations..."
php artisan migrate --force

# Helpful: Seed if users table is empty (Prevent duplication on restarts)
# Note: This is a simple check. Adjust logic if needed.
if [ $(php artisan tinker --execute="echo \App\Models\User::count()") -eq 0 ]; then
    echo "Seeding Database..."
    php artisan db:seed --force
fi

# 6. Storage Link
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# 7. Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm
