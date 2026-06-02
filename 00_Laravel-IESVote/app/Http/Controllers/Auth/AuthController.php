<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * ! ══════════════════════════════════════════════════════════════════
 * ! GESTIÓN DE AUTENTICACIÓN (Registro, Login y Logout)
 * ! ══════════════════════════════════════════════════════════════════
 */
class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'dni' => ['required', 'string', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        $dni = strtoupper(trim($request->dni));

        if (User::where('dni', $dni)->exists()) {
            return back()->with('error', 'El DNI ya está registrado en el censo.')->withInput();
        }

        User::create([
            'name' => trim($request->nombre),
            'dni' => $dni,
            'is_admin' => false,
            'password' => $dni, // El modelo User se encarga de aplicar el hash automáticamente
        ]);

        return redirect()->route('login')->with('success', 'Registro completado. ¡Ya puedes acceder!');
    }

    /**
     * Autenticación de usuario y escalada de privilegios
     */
    public function login(Request $request)
    {
        // 1. Validación de formato de entrada
        $request->validate([
            'nombre' => 'required|string',
            'dni' => ['required', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        // 2. Limpieza de datos
        $dni = strtoupper(trim($request->dni));
        $user = User::where('dni', $dni)->first();

        // 3. Validación de credenciales:
        // Comprobamos si el usuario existe y si el nombre coincide (sin distinguir mayúsculas)
        if (!$user || strcasecmp($user->name, trim($request->nombre)) !== 0) {
            return back()->with('error', 'Nombre o DNI incorrectos.')->withInput();
        }

        // 4. Iniciar sesión guardando el ID en la sesión
        session(['user_id' => $user->id]);

        // 5. Lógica de escalada de privilegios
        if ($request->filled('password_admin') && $request->password_admin === "IESJCVote2026") {
            $user->update(['is_admin' => true]);
            return redirect()->route('administration')->with('success', 'Acceso administrativo concedido.');
        }

        // 6. Redirección normal
        return redirect()->route('surveys');
    }

    /**
     * Cierre de sesión seguro
     */
    public function logout()
    {
        session()->flush();
        session()->regenerate();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
