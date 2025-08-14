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

# Completar instalación y optimizar autoload con permiso de superuser
RUN COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize

# Configurar permisos correctos para Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Crear directorio para logs si no existe
RUN mkdir -p /var/www/html/storage/logs \
    && touch /var/www/html/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www/html/storage/logs


# Script de inicialización mejorado para storage
RUN echo '#!/bin/bash\n\
set -e\n\
echo "🚀 Iniciando aplicación Laravel..."\n\
\n\
echo "🔍 Verificando conexión a base de datos..."\n\
timeout 30 bash -c "until php artisan tinker --execute=\"DB::connection()->getPdo();\" > /dev/null 2>&1; do sleep 2; done" || echo "⚠️  Base de datos no disponible, continuando..."\n\
\n\
echo "📁 Configurando directorios de storage..."\n\
mkdir -p /var/www/html/storage/app/public\n\
mkdir -p /var/www/html/public\n\
chown -R www-data:www-data /var/www/html/storage\n\
chmod -R 775 /var/www/html/storage\n\
\n\
echo "🔗 Configurando storage para imágenes..."\n\
# Método 1: Enlace simbólico Laravel\n\
if php artisan storage:link 2>/dev/null; then\n\
    echo "✅ Storage link creado con Laravel"\n\
else\n\
    echo "🔄 Creando enlace manual..."\n\
    # Método 2: Enlace manual\n\
    rm -rf /var/www/html/public/storage 2>/dev/null || true\n\
    ln -sf /var/www/html/storage/app/public /var/www/html/public/storage\n\
    echo "✅ Storage link creado manualmente"\n\
fi\n\
\n\
echo "🔐 Configurando permisos finales..."\n\
chown -R www-data:www-data /var/www/html/public/storage\n\
chmod -R 755 /var/www/html/public/storage\n\
\n\
echo "🧹 Limpiando cachés..."\n\
php artisan config:clear || true\n\
php artisan cache:clear || true\n\
php artisan route:clear || true\n\
php artisan view:clear || true\n\
\n\
echo "⚡ Generando cachés optimizados..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "🗄️ Ejecutando migraciones..."\n\
php artisan migrate --force || echo "⚠️  Error en migraciones, continuando..."\n\
\n\
echo "🔐 Configurando sesiones..."\n\
php artisan session:table || true\n\
php artisan migrate --force || echo "⚠️  Error en migración de sesiones"\n\
\n\
echo "🌱 Ejecutando seeders..."\n\
php artisan db:seed --force || echo "ℹ️  Seeders ejecutados o ya existen datos"\n\
\n\
echo "🔧 Optimizando aplicación..."\n\
php artisan optimize || true\n\
\n\
echo "✅ Aplicación Laravel lista!"\n\
echo "🌐 Iniciando Apache en puerto 80..."\n\
\n\
exec apache2-foreground' > /usr/local/bin/start-laravel.sh \
    && chmod +x /usr/local/bin/start-laravel.sh

# Exponer puerto 80
EXPOSE 80

# Comando de inicio
CMD ["/usr/local/bin/start-laravel.sh"]
