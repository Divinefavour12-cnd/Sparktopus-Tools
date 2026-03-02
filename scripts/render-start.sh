#!/bin/bash
set -e # Exit on error
set -x # Debug: show commands

echo ">>> Starting Render Initialization..."

# Ensure we are in the right directory
cd /var/www/html

# Check if .env exists, if not generate one from example (APP_KEY is already handled by Render)
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# Fix permissions for folders Laravel needs to write to
echo "Fixing permissions..."
chmod -R 777 storage bootstrap/cache

# Wait for database to be ready (optional but good)
echo "Waiting for database connection..."
# We can use a simple loop to wait for pg_isready if we have postgres-client installed
# But let's just try to migrate with a retry limit
MAX_RETRIES=5
RETRY_COUNT=0
until [ $RETRY_COUNT -ge $MAX_RETRIES ]
do
    echo "Running migrations (Attempt: $((RETRY_COUNT+1))/$MAX_RETRIES)..."
    php artisan migrate --force --no-interaction && break
    RETRY_COUNT=$((RETRY_COUNT+1))
    echo "Migration failed. Retrying in 5 seconds..."
    sleep 5
done

if [ $RETRY_COUNT -eq $MAX_RETRIES ]; then
    echo "!!! Migration failed after several attempts. Check your database credentials on Render."
fi

# Clear and cache optimization
echo "Optimizing application for production..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
# We won't use artisan config:cache yet as it might lock in wrong ENV during build vs runtime
# But we can do it if secrets are loaded.
# php artisan config:cache
# php artisan route:cache

# Set Apache to listen on Render's dynamic port
echo "Configuring Apache for port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo ">>> Initialization complete. Starting Apache..."
apache2-foreground
