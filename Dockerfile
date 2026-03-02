FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    zlib1g-dev \
    libpq-dev \
    tesseract-ocr \
    chromium

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl

# Apache configuration (set DocumentRoot to /public)
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set permissions
# Ensure directory structure exists
RUN mkdir -p storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Set preliminary permissions for build-time commands
RUN chown -R www-data:www-data storage bootstrap/cache

# Install dependencies (skip scripts to avoid cache errors during build)
RUN chmod +x scripts/render-start.sh
RUN composer install --no-dev --optimize-autoloader --no-scripts
# Skip Puppeteer Chromium download during build (installed via apt-get)
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true

# Install npm dependencies
RUN npm install

# Build assets with memory limit to avoid crashing on free tier
RUN NODE_OPTIONS=--max-old-space-size=450 npm run build

# Render expects the server to listen on $PORT
# The startup script will configure Apache to listen on $PORT

CMD ["/var/www/html/scripts/render-start.sh"]
