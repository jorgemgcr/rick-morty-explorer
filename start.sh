#!/bin/bash

# Create database directory if it doesn't exist
mkdir -p /var/www/html/database

# Create database file if it doesn't exist
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Start the application
php artisan serve --host 0.0.0.0 --port 10000
