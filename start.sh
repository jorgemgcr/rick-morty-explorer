#!/bin/bash

# Create database directory if it doesn't exist
mkdir -p /var/www/html/database

# Create database file if it doesn't exist
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Set storage permissions
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

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
