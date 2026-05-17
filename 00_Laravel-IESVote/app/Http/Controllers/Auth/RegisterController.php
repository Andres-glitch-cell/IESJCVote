<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Muestra el formulario de registro
    public function create()
    {
        return view('auth.register');
    }

    // Procesa el formulario y guarda el usuario
    public function store(Request $request)
    {
        // 1. Validar los datos que llegan del formulario
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' busca un campo llamado password_confirmation
        ]);

        // 2. Guardar en la base de datos usando Eloquent
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // [IMPORTANT] Encriptación obligatoria por seguridad
        ]);

        // 3. Iniciar sesión automáticamente al usuario (Opcional)
        auth()->login($user);

        // 4. Redireccionar al panel principal con un mensaje flash de éxito
        return redirect()->route('dashboard')->with('success', '¡Usuario registrado con éxito!');
    }
}