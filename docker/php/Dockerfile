# Gunakan image PHP
FROM php:8.2-cli

# Install ekstensi tambahan (tanpa database)
RUN apt-get update && apt-get install -y \
    libonig-dev unzip wget curl \
    && docker-php-ext-install mbstring \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

# Set workdir ke dalam container
WORKDIR /var/www/www

# Copy semua file dari host ke container
COPY www/ /var/www/www 

# Berikan izin pada storage dan bootstrap/cache
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose port agar bisa diakses dari luar
EXPOSE 5000

# Jalankan PHP
CMD ["php", "-S", "0.0.0.0:5000", "-t", "public"]
