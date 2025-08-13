# API Pineapple - Laravel 11

API RESTful desarrollada con Laravel 11 para la gestión de productos con autenticación, manejo de imágenes y buenas prácticas.

## 📋 Requisitos Previos

- PHP 8.2 o superior
- Composer 2.x
- MySQL 5.7 o superior
- Node.js 18.x o superior (para el frontend)
- Git

## 🛠 Configuración del Entorno Local

## 🚀 Despliegue en Render

1. Crear una nueva base de datos PostgreSQL en Render
2. Tomar nota de las credenciales:
   - Host
   - Puerto (generalmente 5432)
   - Nombre de la base de datos
   - Usuario
   - Contraseña

3. Configurar las variables de entorno en Render:
```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:tu_app_key
APP_DEBUG=false
APP_URL=https://tu-app.onrender.com
FRONTEND_URL=https://pineapple-ruddy.vercel.app

DB_CONNECTION=pgsql
DB_HOST=tu-host.render.com
DB_PORT=5432
DB_DATABASE=tu_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

FILESYSTEM_DISK=public
```

4. Configurar el Build Command en Render:
```bash
composer install --no-dev && 
php artisan config:cache && 
php artisan route:cache && 
php artisan view:cache && 
php artisan storage:link
```

5. Configurar el Start Command:
```bash
php artisan migrate --force && php artisan serve --host 0.0.0.0 --port $PORT
```

## 🛠 Configuración del Entorno Local

### 1. Clonar el Repositorio

```bash
git clone <url-del-repositorio>
cd API_pineaple/backend
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar el Entorno

1. Copiar el archivo de ejemplo de variables de entorno:
```bash
cp .env.example .env
```

2. Generar la clave de la aplicación:
```bash
php artisan key:generate
```

3. Configurar el archivo `.env` con tus datos:
```env
APP_NAME=Laravel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5176

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_pineapple
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Configurar la Base de Datos

1. Configurar la base de datos PostgreSQL:
```sql
CREATE DATABASE api_pineapple;
-- O usar la interfaz de pgAdmin 4
```

2. Ejecutar las migraciones:
```bash
php artisan migrate
```

3. (Opcional) Cargar datos de prueba:
```bash
php artisan db:seed
```

### 5. Configurar el Almacenamiento

1. Crear el enlace simbólico para el almacenamiento:
```bash
php artisan storage:link
```

2. Asegurarse de que los directorios tengan los permisos correctos:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 🚀 Iniciar el Servidor

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000`

## 📚 Documentación de la API

### Endpoints Principales

#### Autenticación
- POST `/api/auth/login` - Iniciar sesión
- POST `/api/auth/register` - Registrar nuevo usuario
- POST `/api/auth/logout` - Cerrar sesión
- GET `/api/auth/me` - Obtener usuario actual

#### Productos
- GET `/api/productos` - Listar productos (paginado)
- GET `/api/productos/{id}` - Ver producto específico
- POST `/api/productos` - Crear nuevo producto
- PUT `/api/productos/{id}` - Actualizar producto
- DELETE `/api/productos/{id}` - Eliminar producto

### Estructura de Datos

#### Producto
```json
{
  "nombre": "string|required",
  "precio": "numeric|required",
  "stock": "integer|required",
  "descripcion": "string|nullable",
  "imagen": "file|nullable|image|max:2048|mimes:jpeg,png,webp"
}
```

### Autenticación

La API utiliza Laravel Sanctum. Para las peticiones autenticadas, incluir el header:
```
Authorization: Bearer {token}
```

## 🧪 Tests

Ejecutar los tests:
```bash
php artisan test
```

## 🔒 Seguridad

- Rate limiting: 60 peticiones por minuto
- Validación de archivos
- HTTPS forzado en producción
- CORS configurado para el frontend

## 💻 Desarrollo

### Comandos Útiles

1. Limpiar caché:
```bash
php artisan optimize:clear
```

2. Regenerar documentación API:
```bash
php artisan l5-swagger:generate
```

3. Ver rutas disponibles:
```bash
php artisan route:list
```

### Logs

Los logs se encuentran en:
- `storage/logs/laravel.log` - Logs generales
- `storage/logs/api.log` - Logs específicos de la API

## 🚧 Mantenimiento

### Actualizaciones

1. Actualizar dependencias:
```bash
composer update
```

2. Ejecutar migraciones pendientes:
```bash
php artisan migrate
```

### Respaldo

1. Respaldar base de datos:
```bash
php artisan backup:run
```

## ⚡ Rendimiento

- Caché implementado para listados
- Paginación: 12 items por página
- Optimización de imágenes
- Soft deletes para productos

## 🐛 Solución de Problemas Comunes

1. **Error de permisos en storage:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

2. **Error en migraciones:**
```bash
php artisan migrate:fresh
```

3. **Limpiar todo el caché:**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## 📈 Monitoreo

- Logs en formato JSON
- Tracking de peticiones API
- Métricas de rendimiento

## 🤝 Contribución

1. Crear rama feature
2. Hacer commit de cambios
3. Crear Pull Request
4. Esperar revisión

## 📝 Notas Importantes

- La API está configurada para CORS con origen en `http://localhost:5176`
- Las imágenes se almacenan en `storage/app/public/imagenes`
- La documentación Swagger está disponible en `/api/documentation`

## 🆘 Soporte

Para reportar problemas, crear un issue en el repositorio con:
1. Descripción del problema
2. Pasos para reproducir
3. Comportamiento esperado
4. Logs relevantes
