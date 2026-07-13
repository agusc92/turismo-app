<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;

// Admin Login
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Panel (protected)
Route::prefix('admin')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
    Route::get('/',                  fn() => view('admin.dashboard'))->name('admin.dashboard');
    Route::get('/eventos',           fn() => view('admin.eventos'))->name('admin.eventos');
    Route::get('/actividades',       fn() => view('admin.actividades'))->name('admin.actividades');
    Route::get('/alojamientos',      fn() => view('admin.alojamientos'))->name('admin.alojamientos');
    Route::get('/balnearios',        fn() => view('admin.balnearios'))->name('admin.balnearios');
    Route::get('/gastronomicos',     fn() => view('admin.gastronomicos'))->name('admin.gastronomicos');
    Route::get('/complejos',         fn() => view('admin.complejos'))->name('admin.complejos');
    Route::get('/usuarios',          fn() => view('admin.usuarios'))->name('admin.usuarios');
    Route::get('/tipos',             fn() => view('admin.tipos'))->name('admin.tipos');
    Route::get('/tipo-gastronomico', fn() => view('admin.tipo-gastronomico'))->name('admin.tipo-gastronomico');
    Route::get('/menus',             fn() => view('admin.menus'))->name('admin.menus');
    Route::get('/tipo-alojamiento',  fn() => view('admin.tipo-alojamiento'))->name('admin.tipo-alojamiento');
});

// Default
Route::get('/', function () {
    return redirect()->route('admin.login');
});
