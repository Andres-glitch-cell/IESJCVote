<?php

namespace App\Http\Controllers\Admin;

// ================================================
// USER CONTROLLER - GESTIÓN DE USUARIOS (ADMIN)
// ================================================
// Controlador encargado de gestionar los usuarios desde el panel de administración.

use App\Http\Controllers\Controller;
use App\Models\User;                    // Modelo de Usuario
use Illuminate\Http\Request;           // Para manejar las peticiones HTTP
use Illuminate\Support\Facades\Hash;   // Para encriptar contraseñas

/**
 * Class UserController
 *
 * Maneja las operaciones CRUD básicas de usuarios para el administrador.
 */
class UserController extends Controller
{
    /**
     * Muestra la lista de todos los usuarios
     * Ruta: GET /admin/users
     */
    // Llistar tots els usuaris
    public function index()
    {
        // Obtiene todos los usuarios de la base de datos
        $users = User::all();

        // Pasa la variable $users a la vista
        return view('admin.users.index', compact('users'));
    }

    /**
     * Almacena un nuevo usuario en la base de datos
     * Ruta: POST /admin/users
     */
    // Donar d'alta un usuari (Crear usuario)
    public function store(Request $request)
    {
        // ================================================
        // VALIDACIÓN DE DATOS
        // ================================================
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'dni' => 'required|unique:users',
            'subcategory_id' => 'required|exists:categories,id',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dni' => $request->dni,
            'subcategory_id' => $request->subcategory_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuari creat correctament.');
    }

    /**
     * * Actualiza un usuario existente (actualmente solo subcategory_id)
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'subcategory_id' => 'required|exists:categories,id',
        ]);

        $user->update([
            'subcategory_id' => $request->subcategory_id,
        ]);

        return back()->with('success', 'Subcategoria actualitzada per a ' . $user->name);
    }

    /**
     * * Elimina un usuario de la base de datos
     * Ruta: DELETE /admin/users/{user}
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuari eliminat.');
    }
}
