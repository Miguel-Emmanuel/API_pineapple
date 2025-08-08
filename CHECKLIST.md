# Checklist de Verificación

## Configuración Inicial

-   [ ] Composer instalado y actualizado
-   [ ] PHP >= 8.2 instalado
-   [ ] PostgreSQL instalado y configurado
-   [ ] Extensiones PHP necesarias habilitadas (pgsql, fileinfo, etc)

## Instalación

-   [ ] Proyecto clonado correctamente
-   [ ] Dependencias instaladas (`composer install`)
-   [ ] .env configurado correctamente
-   [ ] APP_KEY generada
-   [ ] Conexión a base de datos verificada
-   [ ] Migraciones ejecutadas
-   [ ] Seeder ejecutado
-   [ ] Storage link creado

## Funcionalidades

### Autenticación

-   [ ] Registro de usuario funciona
-   [ ] Login funciona y devuelve token
-   [ ] Endpoint /me funciona con token
-   [ ] Logout funciona y revoca token

### Productos

-   [ ] Listado paginado funciona
-   [ ] Ver producto individual funciona
-   [ ] Crear producto funciona
-   [ ] Actualizar producto funciona
-   [ ] Eliminar producto funciona
-   [ ] Subida de imágenes funciona
-   [ ] URLs de imágenes son accesibles

## Almacenamiento

-   [ ] Storage público configurado y funcionando
-   [ ] Symlink creado correctamente
-   [ ] Imágenes accesibles vía URL
-   [ ] Configuración S3 lista (si aplica)

## Seguridad

-   [ ] Validaciones funcionando
-   [ ] CORS configurado correctamente
-   [ ] Rutas protegidas requieren token
-   [ ] Manejo de errores devuelve JSON

## Deploy

-   [ ] Variables de entorno configuradas
-   [ ] Base de datos configurada
-   [ ] Storage configurado
-   [ ] CORS permite origen del frontend
-   [ ] APP_URL configurada correctamente
