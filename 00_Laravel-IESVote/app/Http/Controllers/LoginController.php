<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function verificarElector(Request $request)
    {
        // Busca si existe la combinación exacta de nombre y DNI
        $existe = User::where('nombre', $request->nombre)
            ->where('dni', $request->dni)
            ->exists();

        return response()->json(['existe' => $existe]);
    }
}
