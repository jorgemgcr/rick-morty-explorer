#!/bin/bash

echo "Starting Laravel application setup..."

# Create database directory if it doesn't exist
mkdir -p /app/database
echo "Database directory created"

# Create database file if it doesn't exist
if [ ! -f /app/database/database.sqlite ]; then
    touch /app/database/database.sqlite
    chmod 664 /app/database/database.sqlite
    echo "Database file created"
else
    echo "Database file already exists"
fi

# Set storage permissions
chmod -R 775 /app/storage
chmod -R 775 /app/bootstrap/cache
echo "Permissions set"

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
    echo "Application key generated"
fi

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo "Cache cleared"

# Run migrations with verbose output
echo "Running migrations..."
php artisan migrate --force --verbose

# Check if migrations were successful
if [ $? -eq 0 ]; then
    echo "Migrations completed successfully"
else
    echo "Migrations failed"
    exit 1
fi

# Enable debug mode temporarily
export APP_DEBUG=true

echo "Starting Laravel server..."
# Start the application with detailed error reporting
php artisan serve --host 0.0.0.0 --port 10000
