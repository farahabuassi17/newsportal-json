# -------- Stage 1: Build --------
FROM php:8.2-apache AS build

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application source
COPY src/ /var/www/html/
COPY storage/ /var/www/storage/

# -------- Stage 2: Runtime --------
FROM php:8.2-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy application files
COPY src/ /var/www/html/
COPY storage/ /var/www/storage/

# Set permissions for JSON storage
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage \
    && chown -R www-data:www-data /var/www/html

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/health.php || exit 1
