<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::apiResource('productos', ProductController::class);
});

// ===== RUTAS DE DEPURACIÓN =====

// Ruta para probar conexión a la base de datos
Route::get('test-db', function () {
    try {
        DB::connection()->getPdo();

        return 'Conexión exitosa a la DB!';
    } catch (Exception $e) {
        return 'Error: '.$e->getMessage();
    }
});

// Ruta para verificar que la API funciona
Route::get('health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API funcionando correctamente',
        'timestamp' => now(),
    ]);
});
