#!/bin/bash
# ==============================================================================
# Hostinger Shared Hosting SSH Automated Deployment Script
# Project: Shri Kamadgiri Peetham Sewa Trust Digital Platform
# ==============================================================================

set -e

echo "--------------------------------------------------------"
echo "  Starting Shri Kamadgiri Peetham Platform Deployment   "
echo "--------------------------------------------------------"

# 1. Determine PHP Binary
# Hostinger provides specific PHP CLI aliases (e.g. /usr/bin/php8.3 or system php)
if command -v /usr/bin/php8.3 &> /dev/null; then
    PHP_BIN="/usr/bin/php8.3"
elif command -v /usr/bin/php8.4 &> /dev/null; then
    PHP_BIN="/usr/bin/php8.4"
elif command -v /usr/bin/php8.5 &> /dev/null; then
    PHP_BIN="/usr/bin/php8.5"
elif command -v php &> /dev/null; then
    PHP_BIN="php"
else
    echo "ERROR: PHP CLI binary not found in standard paths."
    exit 1
fi

echo "Using PHP binary: $PHP_BIN ($($PHP_BIN -v | head -n 1))"

# 2. Check for .env file
if [ ! -f ".env" ]; then
    echo "ERROR: .env file is missing!"
    echo "Copy .env.production.example to .env and configure your Hostinger database details before deploying."
    exit 1
fi

# 3. Enter Maintenance Mode (if artisan exists)
if [ -f "artisan" ]; then
    echo ">> Entering maintenance mode..."
    $PHP_BIN artisan down --refresh=15 --retry=60 || true
fi

# 4. Pull Latest Code from Repository (if git is used)
if [ -d ".git" ]; then
    echo ">> Pulling latest changes from git..."
    git pull origin main
fi

# 5. Install Production PHP Dependencies
echo ">> Installing production Composer dependencies..."
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    $PHP_BIN composer.phar install --no-dev --optimize-autoloader --no-interaction
fi

# 6. Database Migrations
echo ">> Running database migrations..."
$PHP_BIN artisan migrate --force

# 7. Clear & Optimize Production Caches
echo ">> Optimizing configuration, routes, and views cache..."
$PHP_BIN artisan config:clear
$PHP_BIN artisan route:clear
$PHP_BIN artisan view:clear

$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache
$PHP_BIN artisan view:cache
$PHP_BIN artisan event:cache

# 8. Create Storage Symlink
echo ">> Verifying storage symlink..."
$PHP_BIN artisan storage:link || true

# 9. Set Storage and Cache Permissions
echo ">> Setting filesystem permissions..."
chmod -R 775 storage bootstrap/cache

# 10. Bring Application Online
echo ">> Exiting maintenance mode..."
$PHP_BIN artisan up

echo "--------------------------------------------------------"
echo "  DEPLOYMENT COMPLETE! Website is live and healthy.     "
echo "--------------------------------------------------------"
