<?php
use Illuminate\Support\Facades\Route;

// # Mostrar formulario
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

?>
