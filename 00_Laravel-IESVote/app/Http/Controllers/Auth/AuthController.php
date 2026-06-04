<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validamos usando 'username' para coincidir con la base de datos
        $request->validate([
            'username' => 'required|string|max:255',
            'dni' => ['required', 'string', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        $dni = strtoupper(trim($request->dni));

        if (User::where('dni', $dni)->exists()) {
            return back()->with('error', 'El DNI ya está registrado.')->withInput();
        }

        // Creamos el usuario con los campos definitivos en inglés
        User::create([
            'username' => trim($request->username),
            'dni' => $dni,
            'is_admin' => false,
            'password' => Hash::make($dni), // Rellenamos la columna 'password' de forma segura
        ]);

        return redirect()->route('login')->with('success', 'Registro completado.');
    }

    public function login(Request $request)
    {
        // Validación adaptada al campo username
        $request->validate([
            'username' => 'required|string',
            'dni' => ['required', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        $dni = strtoupper(trim($request->dni));
        $user = User::where('dni', $dni)->first();

        // Comprobamos si el usuario existe y si el 'username' coincide (sin importar mayúsculas)
        if (!$user || strcasecmp($user->username, trim($request->username)) !== 0) {
            return back()->with('error', 'Usuario o DNI incorrectos.')->withInput();
        }

        // Si la identidad es correcta, regeneramos sesión e iniciamos el login manual
        $request->session()->regenerate();
        Auth::login($user);

        // Tu lógica de administrador con clave secreta de acceso rápido
        if ($request->filled('password_admin') && $request->password_admin === "IESJCVote2026") {
            $user->update(['is_admin' => true]);
            $user->refresh();
            Auth::login($user);

            // Redirige a tu ruta de administración (ej: 'admin.dashboard' o 'administration')
            return redirect()->route('admin.dashboard')->with('success', 'Acceso administrativo.');
        }

        // Redirige al panel de votaciones del alumno/profesor (ej: 'dashboard' o 'surveys')
        return redirect()->intended(route('surveys'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada.');
    }
}