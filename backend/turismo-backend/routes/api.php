<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\GastronomicoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AlojamientoController;
use App\Http\Controllers\BalnearioController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InfoUsuarioController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GastronomicoMenuController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResource('gastronomicos', GastronomicoController::class);
Route::apiResource('actividades', ActividadController::class);
Route::apiResource('alojamientos', AlojamientoController::class);
Route::apiResource('balnearios', BalnearioController::class);
Route::apiResource('tipos', TipoController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('info-usuarios', InfoUsuarioController::class);
Route::apiResource('menus', MenuController::class);

// Evento nested routes
Route::get('eventos/destacados', [EventoController::class, 'destacados']);
Route::apiResource('eventos', EventoController::class);

// Gastronomico menus nested routes
Route::get('gastronomicos/{id}/menus', [GastronomicoMenuController::class, 'index']);
Route::post('gastronomicos/{id}/menus', [GastronomicoMenuController::class, 'store']);
Route::delete('gastronomicos/{id}/menus/{menuId}', [GastronomicoMenuController::class, 'destroy']);

// Protected routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
});
