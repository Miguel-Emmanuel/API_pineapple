#!/bin/bash
set -e

echo "🚀 Iniciando aplicación Laravel en Docker..."

# Función para mostrar logs con timestamp
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

log "🧹 Limpiando cachés de Laravel..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

log "⚡ Generando cachés optimizados para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

log "🔑 Verificando APP_KEY..."
if [ -z "$APP_KEY" ]; then
    log "⚠️  APP_KEY no está configurado, generando uno nuevo..."
    php artisan key:generate --force
fi

log "🗄️ Ejecutando migraciones de base de datos..."
php artisan migrate --force

log "🔐 Configurando tabla de sesiones..."
php artisan session:table --force || true
php artisan migrate --force

log "🌱 Ejecutando seeders (si existen)..."
php artisan db:seed --force || log "ℹ️  No hay seeders para ejecutar"

log "🔧 Configurando permisos finales..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

log "✅ Laravel configurado correctamente!"
log "🌐 Iniciando servidor Apache en puerto 80..."

# Iniciar Apache en foreground
exec apache2-foreground
