# Configuración de Postman para API Pineapple

## Configuración Base

### URL Base
```
http://localhost:8000/api
```
*Ajusta el puerto según tu configuración*

### Headers Comunes
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "X-Requested-With": "XMLHttpRequest"
}
```

### Headers con Autenticación (Sanctum Bearer Token)
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```

## Endpoints de Autenticación

### 1. Registro de Usuario (POST)
**URL:** `{{base_url}}/auth/register`
**Method:** POST
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json"
}
```
**Body (raw JSON):**
```json
{
  "name": "Nuevo Usuario",
  "email": "nuevo@ejemplo.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 2. Login de Usuario (POST)
**URL:** `{{base_url}}/auth/login`
**Method:** POST
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json"
}
```
**Body (raw JSON):**
```json
{
  "email": "tester@example.com",
  "password": "password"
}
```

### 3. Logout (POST)
**URL:** `{{base_url}}/auth/logout`
**Method:** POST
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```

## Endpoints de Productos

### 4. Obtener todos los productos (GET)
**URL:** `{{base_url}}/products`
**Method:** GET
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```

### 5. Crear nuevo producto (POST)
**URL:** `{{base_url}}/products`
**Method:** POST
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```
**Body (raw JSON):**
```json
{
  "nombre": "Producto de Prueba",
  "precio": 99.99,
  "stock": 50,
  "descripcion": "Descripción del producto de prueba",
  "imagen": "imagenes/producto-prueba.png"
}
```

### 6. Obtener producto específico (GET)
**URL:** `{{base_url}}/products/1`
**Method:** GET
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```

### 7. Actualizar producto (PUT)
**URL:** `{{base_url}}/products/1`
**Method:** PUT
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```
**Body (raw JSON):**
```json
{
  "nombre": "Producto Actualizado",
  "precio": 149.99,
  "stock": 25,
  "descripcion": "Descripción actualizada del producto",
  "imagen": "imagenes/producto-actualizado.png"
}
```

### 8. Eliminar producto (DELETE)
**URL:** `{{base_url}}/products/1`
**Method:** DELETE
**Headers:**
```json
{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer {{token}}"
}
```

## Variables de Entorno en Postman

Crear las siguientes variables en tu entorno de Postman:

```json
{
  "base_url": "http://localhost:8000/api",
  "token": ""
}
```

## Configuración de Tests Automáticos

### Test para Registro (JavaScript en Tests tab):
```javascript
pm.test("Registro exitoso", function () {
    pm.response.to.have.status(201);
    
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('user');
    pm.expect(jsonData).to.have.property('token');
    
    // Guardar token para usar en otras peticiones
    pm.environment.set("token", jsonData.token);
});
```

### Test para Login (JavaScript en Tests tab):
```javascript
pm.test("Login exitoso", function () {
    pm.response.to.have.status(200);
    
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('token');
    
    // Guardar token para usar en otras peticiones
    pm.environment.set("token", jsonData.token);
});
```

### Test para Productos (JavaScript en Tests tab):
```javascript
pm.test("Respuesta exitosa", function () {
    pm.response.to.have.status(200);
});

pm.test("Respuesta contiene productos", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('data');
});
```

## Usuario de Prueba Existente

**Email:** `tester@example.com`
**Password:** `password` (basado en el hash de la BD)
