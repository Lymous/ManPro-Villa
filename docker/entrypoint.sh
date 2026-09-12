#!/bin/sh
set -e

echo "==> Starting Laravel Villa Booking container initialization..."

cd /var/www/html

# 1. Pastikan .env tersedia
if [ ! -f .env ]; then
    echo "==> .env file not found. Copying from .env.docker.example or .env.example..."
    if [ -f .env.docker.example ]; then
        cp .env.docker.example .env
    else
        cp .env.example .env
    fi
fi

# 2. Pastikan Composer dependencies terinstall
if [ ! -d vendor ]; then
    echo "==> vendor/ not found. Running composer install..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# 4. Storage symlink
echo "==> Ensuring storage link exists..."
php artisan storage:link || true

# 5. Fix permissions for storage and cache
echo "==> Adjusting permissions for storage & cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 6. Wait for Database
echo "==> Waiting for MySQL database connection..."
until php -r "
    \$host = getenv('DB_HOST') ?: 'db';
    \$port = getenv('DB_PORT') ?: '3306';
    \$database = getenv('DB_DATABASE') ?: 'manpro_villa';
    \$username = getenv('DB_USERNAME') ?: 'root';
    \$password = getenv('DB_PASSWORD') ?: 'admin';
    try {
        new PDO(\"mysql:host={\$host};port={\$port};dbname={\$database}\", \$username, \$password);
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    echo "    Database is unavailable - waiting 2 seconds..."
    sleep 2
done
echo "==> Database connection established successfully!"

# 7. Run Migrations & Seeders
echo "==> Running migrations..."
php artisan migrate --force

# Cek apakah data villa sudah di-seed
if php artisan tinker --execute "echo \Illuminate\Support\Facades\Schema::hasTable('villas') && \App\Models\Villa::count() === 0 ? 'need_seed' : 'ok';" 2>/dev/null | grep -q "need_seed"; then
    echo "==> Seeding initial villa data..."
    php artisan db:seed --class=VillaSeeder --force || true
else
    echo "==> Database already seeded or not required."
fi


# 8. Build frontend assets if not present
if [ ! -f public/build/manifest.json ]; then
    echo "==> Compiling frontend assets (Vite)..."
    if [ ! -f node_modules/.bin/vite ]; then
        echo "==> Installing npm packages inside container..."
        npm install
    fi
    npm run build || echo "==> Frontend asset build warning. Continuing..."
fi

echo "==> Initialization complete! Starting PHP-FPM..."
exec "$@"
