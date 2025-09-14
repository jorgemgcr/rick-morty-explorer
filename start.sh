#!/bin/bash

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Start the application
php artisan serve --host 0.0.0.0 --port 10000
