#!/bin/bash

# Create database directory if it doesn't exist
mkdir -p /app/database

# Create database file if it doesn't exist
if [ ! -f /app/database/database.sqlite ]; then
    touch /app/database/database.sqlite
    chmod 664 /app/database/database.sqlite
fi

# Set storage permissions
chmod -R 775 /app/storage
chmod -R 775 /app/bootstrap/cache

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Enable debug mode temporarily
export APP_DEBUG=true

# Start the application with detailed error reporting
php artisan serve --host 0.0.0.0 --port 10000
