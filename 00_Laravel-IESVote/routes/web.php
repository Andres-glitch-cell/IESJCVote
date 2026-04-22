<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('00_register');
});

Route::post('/surveys', function (Request $request) {

    // IMPORTANT: Introducir controlador a futuro con la linea --> return (new FormController)->store($request);
    // return "Accediendo con el DNI: " . $request->input('dni') . " y nombre: " . $request->input('nombre');
    return view('01_surveys');

})->name('00_register');

Route::get('/surveys', function () {
    return view('01_surveys');
})->name('01_surveys');
