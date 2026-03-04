#!/bin/bash
set -x

echo ">>> Starting Render Initialization..."

cd /var/www/html

chmod -R 777 storage bootstrap/cache

# Clear config to ensure fresh ENV values
php artisan config:clear

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Wipe all tables and migrate fresh
echo "Wiping database..."
php artisan db:wipe --force

echo "Running migrations..."
php artisan migrate --force --no-interaction --seed

# Create storage symlink
echo "Creating storage link..."
php artisan storage:link || true

# Clear all caches
echo "Clearing caches..."
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Configure Apache port
echo "Configuring Apache for port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo ">>> Starting Apache..."
apache2-foreground
