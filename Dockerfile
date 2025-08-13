# Imagen base oficial PHP 8.2 con Apache
FROM php:8.2-apache

# Configurar variables de entorno
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependencias del sistema para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para Laravel
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Habilitar mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de dependencias primero (para cache de Docker)
COPY composer.json composer.lock ./

# Instalar dependencias PHP con permiso de superuser
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar toda la aplicación
COPY . .

# Completar instalación y optimizar autoloader con permiso de superuser
RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoloader --optimize

# Configurar permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Crear directorio para logs si no existe
RUN mkdir -p /var/www/html/storage/logs \
    && touch /var/www/html/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html/storage/logs

# Script de inicialización para Laravel
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Iniciando aplicación Laravel..."\n\
\n\
# Verificar conectividad de base de datos\n\
echo "🔍 Verificando conexión a base de datos..."\n\
timeout 30 bash -c "until php artisan tinker --execute=\"DB::connection()->getPdo();\" > /dev/null 2>&1; do sleep 2; done" || echo "⚠️  Base de datos no disponible, continuando..."\n\
\n\
# Limpiar cachés\n\
echo "🧹 Limpiando cachés..."\n\
php artisan config:clear || true\n\
php artisan cache:clear || true\n\
php artisan route:clear || true\n\
php artisan view:clear || true\n\
\n\
# Generar cachés optimizados\n\
echo "⚡ Generando cachés optimizados..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Ejecutar migraciones\n\
echo "🗄️ Ejecutando migraciones..."\n\
php artisan migrate --force || echo "⚠️  Error en migraciones, continuando..."\n\
\n\
# Configurar tabla de sesiones\n\
echo "🔐 Configurando sesiones..."\n\
php artisan session:table --force || true\n\
php artisan migrate --force || echo "⚠️  Error en migración de sesiones"\n\
\n\
# Ejecutar seeders si existen\n\
echo "🌱 Ejecutando seeders..."\n\
php artisan db:seed --force || echo "ℹ️  No hay seeders o error en seeders"\n\
\n\
# Optimizar aplicación\n\
echo "🔧 Optimizando aplicación..."\n\
php artisan optimize || true\n\
\n\
echo "✅ Aplicación Laravel lista!"\n\
echo "🌐 Iniciando Apache en puerto 80..."\n\
\n\
# Iniciar Apache en foreground\n\
exec apache2-foreground' > /usr/local/bin/start-laravel.sh \
    && chmod +x /usr/local/bin/start-laravel.sh

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["/usr/local/bin/start-laravel.sh"]