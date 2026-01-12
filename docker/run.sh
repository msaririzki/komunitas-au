#!/bin/sh

cd /var/www

echo "🚀 Starting Deployment Script..."

# 1. Environment Setup
if [ ! -f .env ]; then
    echo "📄 Creating .env file from example..."
    cp .env.example .env
    php artisan key:generate
fi

# Force DB_HOST to match container name (fix for existing .env issues)
if grep -q "DB_HOST=" .env; then
    sed -i 's/DB_HOST=.*/DB_HOST=au_db/' .env
else
    echo "DB_HOST=au_db" >> .env
fi

# 2. Permissions (Fix for Local/Server consistency)
echo "🔒 Fixing Permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# 3. PHP Dependencies
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader --no-dev
fi

# 4. Node Dependencies & Build
if [ ! -d "node_modules" ] || [ ! -d "public/build" ]; then
    echo "🎨 Installing Node dependencies and building assets..."
    npm install
    npm run build
fi

# 5. Wait for Database (Robust Check)
echo "⏳ Waiting for Database connection..."
MAX_RETRIES=60
COUNT=0
while ! php artisan db:show; do
    echo "   ...waiting for mysql ($COUNT/$MAX_RETRIES)"
    sleep 2
    COUNT=$((COUNT+1))
    if [ $COUNT -ge $MAX_RETRIES ]; then
        echo "❌ Database connection failed after timeout."
        exit 1
    fi
done
echo "✅ Database is connected!"

# 6. Database Migration & Seed
echo "🔄 Running Migrations..."
php artisan migrate --force

# Seed only if no users exist
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count()" | tail -n 1)
if [ "$USER_COUNT" -eq "0" ]; then
    echo "🌱 Seeding Database with Dummy Data..."
    php artisan db:seed --force
else
    echo "✨ Database already populated. Skipping seed."
fi

# 7. Storage Link
if [ ! -L "public/storage" ]; then
    echo "🔗 Linking Storage..."
    php artisan storage:link
fi

# 8. Start PHP-FPM
echo "🏁 Setup Complete! Starting PHP-FPM..."
# 8. Start PHP-FPM
# 8. Start PHP-FPM
echo "🏁 Setup Complete! Starting PHP-FPM..."
# Fix ownership one last time before starting (in case migrations created root-owned files)
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
php-fpm
