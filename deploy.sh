#!/bin/bash
# deploy.sh - Safe Deployment Script

echo "Starting Safe Deployment..."

# 1. Maintenance Mode
echo "Putting application in maintenance mode..."
php artisan down || true

# 2. Update Codebase (Assumes git pull is done separately or added here)
# git pull origin main

# 3. Install Dependencies
echo "Installing dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# 4. Migrate Database
echo "Running database migrations..."
php artisan migrate --force

# 5. Seed Static Data (Safe/Idempotent)
# This will safely create missing Roles, Permissions, and default settings
# without overwriting existing critical data (users, employees, etc.)
echo "Updating static data (Roles, Permissions, Defaults)..."
php artisan db:seed --force

# 6. Clear Caches
echo "Clearing caches..."
php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache

# 7. Exit Maintenance Mode
echo "Bringing application back online..."
php artisan up

echo "Deployment Complete!"
