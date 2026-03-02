#!/bin/bash

# Ensure storage and bootstrap directories are writable
chmod -R 777 storage bootstrap/cache

# Run migrations if necessary
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Optimize for production
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set Apache to listen on the port provided by Render
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo "Starting Apache on port $PORT..."
apache2-foreground
