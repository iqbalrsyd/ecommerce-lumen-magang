# Menggunakan PHP 8.1 CLI
FROM php:8.2-cli

# Install ekstensi yang diperlukan
RUN apt-get update && apt-get install -y \
    unzip curl git zip \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy hanya file composer terlebih dahulu untuk caching
COPY www/composer.json www/composer.lock /var/www/

# Install dependensi dengan Composer (tanpa development dependencies)
RUN composer install --no-dev --optimize-autoloader

# Copy seluruh proyek setelah dependensi di-install
COPY www /var/www/

# Konfigurasi izin folder storage & cache untuk Lumen
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache


# Expose port untuk menjalankan PHP CLI
EXPOSE 9000

# Perintah default untuk menjalankan Lumen dengan PHP CLI
CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]