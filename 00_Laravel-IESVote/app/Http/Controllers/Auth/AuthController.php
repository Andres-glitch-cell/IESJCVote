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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'dni' => ['required', 'string', 'regex:/^\d{8}[A-Z]$/'],
            'role' => ['required', 'in:alumno,profesor,padre'],
        ]);

        $dni = strtoupper(trim($request->dni));

        if (User::where('dni', $dni)->exists()) {
            return back()->with('error', 'El DNI ya está registrado.')->withInput();
        }

        User::create([
            'name' => trim($request->nombre),
            'dni' => $dni,
            'role' => $request->role, // ✅ guardamos el rol
            'is_admin' => false,
            'password' => Hash::make($dni),
        ]);

        return redirect()->route('login')->with('success', 'Registro completado.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'dni' => ['required', 'regex:/^\d{8}[A-Z]$/'],
        ]);

        $dni = strtoupper(trim($request->dni));
        $user = User::where('dni', $dni)->first();

        if (!$user || strcasecmp($user->name, trim($request->nombre)) !== 0) {
            return back()->with('error', 'Usuario o DNI incorrectos.')->withInput();
        }

        $request->session()->regenerate();
        Auth::login($user);

        if ($request->filled('password_admin') && $request->password_admin === "IESJCVote2026") {
            $user->update(['is_admin' => true]);
            $user->refresh();
            Auth::login($user);
            return redirect()->route('admin.dashboard')->with('success', 'Acceso administrativo.');
        }

        return redirect()->intended(route('surveys'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}
