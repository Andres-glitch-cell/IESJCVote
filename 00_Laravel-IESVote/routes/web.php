<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] VISTAS PÚBLICAS Y ACCESOS INICIALES
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::get('/', fn() => view('01_login'))->name('home');
Route::get('/login', fn() => view('01_login'))->name('login');
Route::get('/register', fn() => view('00_register'))->name('register');

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] AUTENTICACIÓN Y GESTIÓN DE SESIÓN
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/login/verificar-elector', [AuthController::class, 'verificarElector'])->name('login.verificar');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] RUTAS PROTEGIDAS (Requieren autenticación)
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::middleware(['auth'])->group(function () {

    // [GROUP] ENCUESTAS Y LÓGICA DE VOTACIÓN
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys');
    Route::post('/surveys/vote', [SurveyController::class, 'vote'])->name('surveys.vote');
    Route::get('/surveys/receipt', [SurveyController::class, 'receipt'])->name('surveys.receipt');
    Route::get('/surveys/receipt/last', [SurveyController::class, 'showLastReceipt'])->name('surveys.last_receipt');

    // [GROUP] PERFIL E HISTORIAL
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/history', [ProfileController::class, 'history'])->name('history');

    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
    // [IMPORTANT] PANEL DE ADMINISTRACIÓN (Acceso restringido a ADMIN)
    // ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

    // Aquí el middleware 'admin' verifica que el usuario sea administrador
    Route::middleware(['admin'])->group(function () {
        Route::get('/administration', [SurveyController::class, 'adminIndex'])->name('administration');
        Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
        Route::post('/surveys/{survey}/toggle', [SurveyController::class, 'toggle'])->name('surveys.toggle');
        Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    });
});

// DEBUG TEMPORAL - borra después
Route::get('/test-auth', function () {
    return response()->json([
        'auth' => auth()->check(),
        'user' => auth()->user(),
        'session' => session()->all(),
    ]);
});
