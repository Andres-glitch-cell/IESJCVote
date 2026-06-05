<?php

namespace App\Http\Controllers\Auth;

// ================================================
// AUTH CONTROLLER - AUTENTICACIÓN
// ================================================
// Maneja el registro, login y logout de usuarios.

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;           // Para manejar datos del formulario
use App\Models\User;                   // Modelo de Usuario
use Illuminate\Support\Facades\Auth;   // Para autenticación (login/logout)
use Illuminate\Support\Facades\Hash;   // Para encriptar contraseñas


class AuthController extends Controller
{
    /**
     * * REGISTRO DE NUEVOS USUARIOS
     * */
    public function register(Request $request)
    {
        // Validación de los datos recibidos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'dni' => ['required', 'string', 'regex:/^\d{8}[A-Z]$/'],
            'role' => ['required', 'in:alumno,profesor,padre'],       // ? Solo permite estos roles permitidos
        ]);

        $dni = strtoupper(trim($request->dni));

        // [IMPORTANT]: Verificamos si el DNI ya existe para evitar duplicados
        if (User::where('dni', $dni)->exists()) {
            return back()->with('error', 'El DNI ya está registrado.')->withInput();
        }

        // Creamos el usuario
        User::create([
            'name' => trim($request->nombre),
            'dni' => $dni,
            'role' => $request->role,
            'is_admin' => false,                    // * Por defecto no es admin
            'password' => Hash::make($dni),         // * Contraseña = DNI (encriptado)
        ]);

        return redirect()->route('login')
            ->with('success', 'Registro completado.');
    }

    /**
     * INICIO DE SESIÓN
     * Sistema personalizado: Login con Nombre + DNI
     * * Opción de clave maestra para acceder como administrador
     */
    public function login(Request $request)
    {
        // Validación básica de los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'dni' => ['required', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        $dni = strtoupper(trim($request->dni));

        $user = User::where('dni', $dni)->first();

        // # Verificamos que exista y filtramos con trim(nombre) para evitar errores de espacios
        if (!$user || strcasecmp($user->name, trim($request->nombre)) !== 0) {
            return back()->with('error', 'Usuario o DNI incorrectos.')->withInput();
        }

        // [IMPORTANT]: Regeneramos la sesión para prevenir ataques de fijación de sesión (Se mantienen los datos del usuario, pero se cambia el ID de sesión)
        $request->session()->regenerate();

        Auth::login($user);

        // ================================================
        // ACCESO ADMINISTRADOR (Clave maestra)
        // ================================================
        if ($request->filled('password_admin') && $request->password_admin === "IESJCVote2026") {
            // ? Volvemos a refrescar digamos el usuario y le redirigimos al panel de administración
            $user->update(['is_admin' => true]);
            $user->refresh();
            Auth::login($user);
            return redirect()->route('admin.dashboard')
                ->with('success', 'Acceso administrativo.');
        }

        return redirect()->intended(route('surveys'));
    }

    /**
     * * CERRAR SESIÓN (LOGOUT)
     *
     */
    public function logout(Request $request)
    {
        Auth::logout();
        // Medidas de seguridad importantes:
        $request->session()->invalidate();      // ? Invalidamos toda la sesión
        $request->session()->regenerateToken(); // ? Generamos un nuevo token CSRF

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
