# Ejemplos de uso de la API

## Autenticación

### Registro
```bash
curl -X POST "http://localhost:8000/api/auth/register" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'
```

### Login
```bash
curl -X POST "http://localhost:8000/api/auth/login" \
  -H "Accept: application/json" \
  -d 'email=tester@example.com&password=password'
```

### Obtener usuario actual
```bash
curl -X GET "http://localhost:8000/api/auth/me" \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Accept: application/json"
```

### Logout
```bash
curl -X POST "http://localhost:8000/api/auth/logout" \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Accept: application/json"
```

## Productos

### Listar productos (paginados)
```bash
curl -X GET "http://localhost:8000/api/productos" \
  -H "Accept: application/json"
```

### Obtener un producto
```bash
curl -X GET "http://localhost:8000/api/productos/1" \
  -H "Accept: application/json"
```

### Crear producto
```bash
curl -X POST "http://localhost:8000/api/productos" \
  -H "Authorization: Bearer <TOKEN>" \
  -F "nombre=Prueba" \
  -F "precio=10.50" \
  -F "stock=5" \
  -F "descripcion=xxx" \
  -F "imagen=@/ruta/a/file.jpg"
```

### Actualizar producto
```bash
curl -X POST "http://localhost:8000/api/productos/1" \
  -H "Authorization: Bearer <TOKEN>" \
  -F "_method=PUT" \
  -F "nombre=Prueba Actualizada" \
  -F "precio=15.50" \
  -F "stock=10" \
  -F "descripcion=xxx actualizado" \
  -F "imagen=@/ruta/a/nueva-imagen.jpg"
```

### Eliminar producto
```bash
curl -X DELETE "http://localhost:8000/api/productos/1" \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Accept: application/json"
```
