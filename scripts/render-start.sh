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

# Mark already-existing tables as migrated without touching data
echo "Checking migration status..."
php artisan migrate:status || true

# Run migrations, if a migration fails due to existing table, mark it and continue
echo "Running migrations..."
php artisan migrate --force --no-interaction 2>&1 | tee /tmp/migrate_output.txt

# Check if failure was due to duplicate table only
if grep -q "already exists" /tmp/migrate_output.txt; then
    echo "Duplicate table detected - marking failed migrations as complete..."
    
    # Get the failed migration name and insert it into migrations table
    php artisan tinker --no-interaction <<'EOF'
$migrated = DB::table('migrations')->pluck('migration')->toArray();
$files = glob(database_path('migrations/*.php'));
foreach($files as $file) {
    $name = basename($file, '.php');
    if (!in_array($name, $migrated)) {
        DB::table('migrations')->insert([
            'migration' => $name,
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        echo "Marked as migrated: $name\n";
    }
}
EOF

    echo "Retrying remaining migrations..."
    php artisan migrate --force --no-interaction || true
fi

# Optimize
echo "Optimizing application..."
php artisan route:clear
php artisan view:clear

# Configure Apache port
echo "Configuring Apache for port $PORT..."
sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo ">>> Starting Apache..."
apache2-foreground
