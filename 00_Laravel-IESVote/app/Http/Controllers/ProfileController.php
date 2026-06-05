<?php

namespace App\Http\Controllers;

use App\Models\VoteRecorded;

class ProfileController extends Controller
{
    // Muestra los datos del perfil
    public function show()
    {
        $user = auth()->user();
        return view('05_profile', compact('user'));
    }

    // Muestra el historial de votos del usuario
    public function history()
    {
        $user = auth()->user();

        $votos = VoteRecorded::with('survey')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('06_history', compact('votos', 'user'));
    }
}
