<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VoteRecorded;

/**
 * 👤 ══════════════════════════════════════════════════════════════════
 * 👤 GESTIÓN DE PERFIL E HISTORIAL DE VOTOS
 * ══════════════════════════════════════════════════════════════════════
 */
class ProfileController extends Controller
{
    /**
     * ? Muestra los datos del perfil
     */
    public function show()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user)
            return redirect()->route('home');

        return view('05_profile', compact('user'));
    }

    /**
     * * Muestra el historial de votos del usuario
     */
    public function history()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user)
            return redirect()->route('home');

        $votos = VoteRecorded::with('survey')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('06_history', compact('votos', 'user'));
    }

    /**
     * TODO Método auxiliar para evitar repetir la búsqueda del usuario en sesión
     */
    private function getAuthenticatedUser()
    {
        return session()->has('user_id') ? User::find(session('user_id')) : null;
    }
}