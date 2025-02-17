# Gunakan image PHP dengan FPM
FROM php:8.1-fpm

# Install ekstensi tambahan yang diperlukan oleh Laravel/Lumen
RUN apt-get update && apt-get install -y \
    libonig-dev unzip wget curl git zip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install mbstring pdo pdo_mysql gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer dari image resmi untuk efisiensi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer 

# Set direktori kerja ke dalam container
WORKDIR /var/www

# Copy semua file dari host ke dalam container
COPY . .

# Pastikan folder storage dan cache ada sebelum mengatur izin
RUN mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose port yang digunakan oleh Nginx/PHP-FPM
EXPOSE 9000

# Jalankan PHP-FPM sebagai default command
CMD ["php-fpm"]
