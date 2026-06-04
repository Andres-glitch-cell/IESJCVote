<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// VISTAS PÚBLICAS
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
Route::get('/', fn() => view('01_login'))->name('home');
Route::get('/login', fn() => view('01_login'))->name('login');
Route::get('/register', fn() => view('00_register'))->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// RUTAS PROTEGIDAS (Requieren autenticación)
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
Route::middleware(['auth'])->group(function () {

    // [GROUP] ENCUESTAS Y LÓGICA DE VOTACIÓN
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys');
    Route::get('/surveys/receipt', [SurveyController::class, 'receipt'])->name('surveys.receipt');
    Route::get('/surveys/receipt/last', [SurveyController::class, 'showLastReceipt'])->name('surveys.last_receipt');

    Route::post('/surveys/vote', [SurveyController::class, 'vote'])->name('surveys.vote');

    // [GROUP] PERFIL E HISTORIAL
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/history', [ProfileController::class, 'history'])->name('history');

    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
    // PANEL DE ADMINISTRACIÓN (Acceso restringido a ADMIN)
    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
    Route::middleware(['admin'])->group(function () {

        // Gestión de Encuestas
        Route::get('/admin/panel', [SurveyController::class, 'adminIndex'])->name('admin.panel');
        Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
        Route::post('/surveys/{survey}/toggle', [SurveyController::class, 'toggle'])->name('surveys.toggle');
        Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

        // Resultados y exportación
        Route::get('/admin/surveys/{survey}/results', [SurveyController::class, 'results'])->name('admin.surveys.results');
        Route::get('/admin/surveys/{survey}/export', [SurveyController::class, 'export'])->name('admin.surveys.export');

        // Dashboard Admin (NOMBRE CORREGIDO: ahora coincide con lo que AuthController espera)
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Gestión de Usuarios
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
