#!/bin/bash

set -e  # Exit on any error

echo "🚀 Starting Laravel application setup..."

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to log with timestamp
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Check if we're in the right directory
if [ ! -f "/app/artisan" ]; then
    log "❌ Error: artisan file not found. Are we in the right directory?"
    exit 1
fi

log "✅ Laravel application found"

# Create database directory and file
log "📁 Setting up database..."
mkdir -p /app/database
if [ ! -f /app/database/database.sqlite ]; then
    touch /app/database/database.sqlite
    chmod 664 /app/database/database.sqlite
    log "✅ Database file created"
else
    log "✅ Database file already exists"
fi

# Set proper permissions
log "🔐 Setting permissions..."
chmod -R 775 /app/storage
chmod -R 775 /app/bootstrap/cache
chmod 664 /app/database/database.sqlite
log "✅ Permissions set"

# Generate application key if not exists
if [ -z "$APP_KEY" ]; then
    log "🔑 Generating application key..."
    php artisan key:generate --force
    log "✅ Application key generated"
else
    log "✅ Application key already exists"
fi

# Clear all caches
log "🧹 Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true
log "✅ Caches cleared"

# Check if database is accessible
log "🔍 Testing database connection..."
if php artisan tinker --execute="DB::connection()->getPdo();" >/dev/null 2>&1; then
    log "✅ Database connection successful"
else
    log "❌ Database connection failed"
    exit 1
fi

# Run migrations
log "🗄️ Running database migrations..."
if php artisan migrate --force --verbose; then
    log "✅ Migrations completed successfully"
else
    log "❌ Migrations failed"
    exit 1
fi

# Check if Node.js is available and build assets
if command_exists node && command_exists npm; then
    log "📦 Node.js and npm are available"
    
    # Check if assets need to be built
    if [ ! -f "/app/public/build/manifest.json" ]; then
        log "🏗️ Building frontend assets..."
        if npm run build; then
            log "✅ Frontend assets built successfully"
        else
            log "⚠️ Frontend build failed, but continuing..."
        fi
    else
        log "✅ Frontend assets already built"
    fi
else
    log "⚠️ Node.js/npm not available, skipping frontend build"
fi

# Fetch characters data
log "📡 Fetching characters data from Rick & Morty API..."
if php artisan tinker --execute="
    \$controller = new \App\Http\Controllers\CharacterController();
    \$response = \$controller->fetch(request());
    echo 'Fetch result: ' . json_encode(\$response->getData());
"; then
    log "✅ Characters data fetched successfully"
else
    log "⚠️ Characters data fetch failed, but continuing..."
fi

# Set environment variables
export APP_DEBUG=true
export APP_ENV=production

# Final checks
log "🔍 Final application checks..."

# Check if Laravel is working
if php artisan --version >/dev/null 2>&1; then
    log "✅ Laravel is working"
else
    log "❌ Laravel is not working"
    exit 1
fi

# Check if database tables exist
if php artisan tinker --execute="echo 'Tables: ' . count(DB::select('SELECT name FROM sqlite_master WHERE type=\"table\"'));" >/dev/null 2>&1; then
    log "✅ Database tables are accessible"
else
    log "❌ Database tables are not accessible"
    exit 1
fi

log "🎉 Application setup completed successfully!"
log "🌐 Starting Laravel server on port 10000..."

# Start the application
exec php artisan serve --host=0.0.0.0 --port=10000
