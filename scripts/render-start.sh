#!/bin/bash
set -x # Debug: show commands

echo ">>> Starting Render Initialization..."

cd /var/www/html

# Fix permissions first
echo "Fixing permissions..."
chmod -R 777 storage bootstrap/cache

# Clear config cache to ensure fresh ENV values
php artisan config:clear

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Wait for database and run migrations
echo "Waiting for database connection..."
MAX_RETRIES=5
RETRY_COUNT=0
until [ $RETRY_COUNT -ge $MAX_RETRIES ]
do
    echo "Running migrations (Attempt: $((RETRY_COUNT+1))/$MAX_RETRIES)..."
    php artisan migrate --force --no-interaction && break
    RETRY_COUNT=$((RETRY_COUNT+1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "!!! Migration failed after several attempts."
        break
    fi
    echo "Migration failed. Retrying in 5 seconds..."
    sleep 5
done

# Optimize
echo "Optimizing application..."
php artisan route:clear
php artisan view:clear

# Configure Apache port
echo "Configuring Apache for port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo ">>> Starting Apache..."
apache2-foreground
