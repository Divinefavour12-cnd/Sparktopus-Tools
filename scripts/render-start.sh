#!/bin/bash
set -x

echo ">>> Starting Render Initialization..."

cd /var/www/html

chmod -R 777 storage bootstrap/cache

php artisan config:clear

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Drop all tables and migrate fresh to fix duplicate table issues
echo "Running fresh migrations..."
php artisan migrate:fresh --force --no-interaction --seed

if [ $? -ne 0 ]; then
    echo "!!! Fresh migration failed, trying regular migrate..."
    php artisan migrate --force --no-interaction || true
fi

# Create storage symlink
php artisan storage:link || true

# Optimize
echo "Optimizing application..."
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Configure Apache port
echo "Configuring Apache for port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo ">>> Starting Apache..."
apache2-foreground
