<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;



/**
 * Rutas públicas - Cualquier persona puede acceder sin estar logueado
 */

Route::get('/', fn() => view('01_login'))->name('home');
Route::get('/login', fn() => view('01_login'))->name('login');

// Ruta para mostrar el formulario de registro
Route::get('/register', fn() => view('00_register'))->name('register');

// ================================================
// ** RUTAS POST (Procesamiento de formularios)
// ================================================

// Procesar el registro de un nuevo usuario
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Procesar el inicio de sesión
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Si fuera GET, causaría el error 419 Page Expired
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// RUTAS PROTEGIDAS (Requieren estar autenticado)
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

/**
 * Todas las rutas dentro de este grupo requieren que el usuario esté logueado
 * gracias al middleware 'auth'
 */
Route::middleware(['auth'])->group(function () {

    // ================================================
    // ** GRUPO 1: ENCUESTAS Y VOTACIÓN
    // ================================================
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys');
    Route::get('/surveys/receipt', [SurveyController::class, 'receipt'])->name('surveys.receipt');
    Route::get('/surveys/receipt/last', [SurveyController::class, 'showLastReceipt'])->name('surveys.last_receipt');
    Route::post('/surveys/vote', [SurveyController::class, 'vote'])->name('surveys.vote');

    // ================================================
    // ** GRUPO 2: PERFIL DE USUARIO E HISTORIAL
    // ================================================
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/history', [ProfileController::class, 'history'])->name('history');


    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
    // PANEL DE ADMINISTRACIÓN
    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

    /**
     * Rutas de mantenimiento
     * [IMPORTANT]: Están dentro de 'auth' pero FUERA del middleware 'admin'
     * Esto permite que cualquier usuario logueado vea la página de "en mantenimiento"
     */
    Route::get('/admin/maintenance', function () {
        return view('admin.maintenance');
    })->name('admin.maintenance');

    Route::get('/admin/toggle-maintenance', function () {
        return view('admin.toggle-maintenance');
    })->name('admin.toggle.maintenance');


    /**
     * Grupo exclusivo solo para administradores
     * [IMPORTANT]: Requiere el middleware 'admin' (EnsureUserIsAdmin)
     */
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // ================================================
        // ** GESTIÓN DE ENCUESTAS
        // ================================================
        Route::get('/admin/panel', [SurveyController::class, 'adminIndex'])->name('admin.panel');
        Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
        Route::post('/surveys/{survey}/toggle', [SurveyController::class, 'toggle'])->name('surveys.toggle');
        Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');

        Route::get('/admin/surveys/{survey}/results', [SurveyController::class, 'results'])->name('admin.surveys.results');
        Route::get('/admin/surveys/{survey}/export', [SurveyController::class, 'export'])->name('admin.surveys.export');

        // ================================================
        // ** GESTIÓN DE USUARIOS
        // ================================================
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
