<?php

use Illuminate\Support\Facades\Route;

/* SVG de Laravel
Route::get('/', function () {
    return view('welcome');
});
*/

Route::view('/', 'dashboard');

