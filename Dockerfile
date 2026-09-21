FROM php:8.2-cli

# Install system dependencies and required tools
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    sqlite3 \
    nodejs \
    npm

# Clear apt cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions for Laravel & CMS
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /app

# Copy existing application directory contents
COPY . /app

# Set appropriate permissions (useful if mapped volumes have strict permissions)
RUN chown -R www-data:www-data /app

# Expose port 8000 for the Artisan server
EXPOSE 8000

# Start Laravel development server by default
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
