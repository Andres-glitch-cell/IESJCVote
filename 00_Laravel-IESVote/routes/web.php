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

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] ENCUESTAS Y LÓGICA DE VOTACIÓN
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys');
Route::post('/surveys/vote', [SurveyController::class, 'vote'])->name('surveys.vote');
Route::get('/surveys/receipt', [SurveyController::class, 'receipt'])->name('surveys.receipt');

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] PERFIL E HISTORIAL
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::get('/history', [ProfileController::class, 'history'])->name('history');

// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
// [IMPORTANT] PANEL DE ADMINISTRACIÓN (Acceso restringido)
// ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬

Route::get('/administration', [SurveyController::class, 'adminIndex'])->name('administration');
Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
Route::post('/surveys/{survey}/toggle', [SurveyController::class, 'toggle'])->name('surveys.toggle');
Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
