<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Llistar tots els usuaris
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Donar d'alta un usuari
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'dni' => 'required|unique:users',
            'subcategory_id' => 'required|exists:categories,id', // O la teua taula de subcategories
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'dni' => $request->dni,
            'subcategory_id' => $request->subcategory_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuari creat correctament.');
    }

    // Editar i assignar subcategoria
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

    // Donar de baixa (Eliminar)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuari eliminat.');
    }
}
