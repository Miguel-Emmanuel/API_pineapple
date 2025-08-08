<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
Route::post('auth/register',[AuthController::class,'register']);
Route::post('auth/login',[AuthController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){
  Route::get('auth/me',[AuthController::class,'me']);
  Route::post('auth/logout',[AuthController::class,'logout']);
  Route::apiResource('productos', ProductController::class);
});
