<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey; // 1. IMPORTANTE: Importa el modelo Survey

class DashboardController extends Controller
{
    public function index()
    {
        // 2. Obtenemos todas las encuestas (o las que necesites)
        $surveys = Survey::all();

        // 3. Enviamos la variable $surveys a la vista
        return view('03_administration', compact('surveys'));
    }
}