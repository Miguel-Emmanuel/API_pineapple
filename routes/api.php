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
        $connection = DB::connection();
        $pdo = $connection->getPdo();

        return response()->json([
            'status' => 'success',
            'message' => 'Conexión exitosa a la DB!',
            'database' => [
                'driver' => config('database.default'),
                'host' => config('database.connections.'.config('database.default').'.host'),
                'port' => config('database.connections.'.config('database.default').'.port'),
                'database' => config('database.connections.'.config('database.default').'.database'),
                'username' => config('database.connections.'.config('database.default').'.username'),
            ],
            'tables_count' => DB::select('SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = ?', [config('database.connections.'.config('database.default').'.database')])[0]->count ?? 'N/A',
        ]);
    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'database_config' => [
                'driver' => config('database.default'),
                'host' => config('database.connections.'.config('database.default').'.host'),
                'port' => config('database.connections.'.config('database.default').'.port'),
                'database' => config('database.connections.'.config('database.default').'.database'),
            ],
        ], 500);
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
