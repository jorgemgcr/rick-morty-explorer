# Use Node.js 18 as base and install PHP
FROM node:18-slim

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    supervisor \
    software-properties-common

# Install PHP 8.3
RUN curl -fsSL https://packages.sury.org/php/apt.gpg | apt-key add - \
    && echo "deb https://packages.sury.org/php/ bullseye main" | tee /etc/apt/sources.list.d/php.list \
    && apt-get update \
    && apt-get install -y php8.3-cli php8.3-mbstring php8.3-xml php8.3-zip php8.3-sqlite3 php8.3-gd

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy package files first for better caching
COPY package*.json ./

# Install Node.js dependencies
RUN npm install

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Build frontend assets
RUN npm run build

# Set proper permissions
RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app
RUN chmod +x /app/start.sh

# Create necessary directories
RUN mkdir -p /app/database
RUN mkdir -p /app/storage/logs
RUN mkdir -p /app/storage/framework/cache
RUN mkdir -p /app/storage/framework/sessions
RUN mkdir -p /app/storage/framework/views
RUN mkdir -p /app/bootstrap/cache

# Expose port
EXPOSE 10000

# Start the application using the start.sh script
CMD ["/app/start.sh"]
