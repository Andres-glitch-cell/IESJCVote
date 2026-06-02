<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Option;
use App\Models\User;
use App\Models\VoteRecorded;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
     * ! FILTRO DE SEGURIDAD (Privado)
     * ! ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬
     */
    private function ensureAdmin()
    {
        if (!session()->has('user_id'))
            return redirect()->route('login');

        $user = User::find(session('user_id'));
        if (!$user || !$user->is_admin)
            return false;

        return $user;
    }

    /**
     * * Muestra la lista de encuestas para los usuarios
     */
    public function index()
    {
        if (!session()->has('user_id'))
            return redirect()->route('login')->with('error', 'Debes iniciar sesión.');

        $surveys = Survey::where('is_active', true)->with('options')->latest()->get();
        return view('02_surveys', compact('surveys'));
    }

    /**
     * * Procesa el voto del usuario
     */
    public function vote(Request $request)
    {
        if (!session()->has('user_id'))
            return redirect()->route('login');

        $request->validate(['option_id' => 'required|exists:options,id']);

        $option = Option::findOrFail($request->option_id);

        // Incrementar votos y registrar
        $option->increment('votes');
        VoteRecorded::create([
            'user_id' => session('user_id'),
            'survey_id' => $option->survey_id,
            'vote_hash' => bin2hex(random_bytes(16))
        ]);

        return redirect()->route('surveys.receipt')->with('success', '¡Voto registrado!');
    }

    /**
     * * Recibo de confirmación de voto
     */
    public function receipt()
    {
        return view('04_receipt');
    }

    /**
     * ? Panel de administración
     */
    public function adminIndex()
    {
        if (!$user = $this->ensureAdmin())
            return redirect()->route('surveys')->with('error', 'No autorizado.');

        $surveys = Survey::with('options')->latest()->get();
        return view('03_administration', compact('surveys'));
    }

    /**
     * * Crear una nueva encuesta
     */
    public function store(Request $request)
    {
        if (!$user = $this->ensureAdmin())
            return redirect()->route('surveys')->with('error', 'No autorizado.');

        $request->validate([
            'title' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $opcionesLimpias = array_filter(array_map('trim', $request->options));
        if (count($opcionesLimpias) < 2)
            return back()->with('error', 'Debes incluir al menos 2 opciones.');

        $survey = Survey::create(['title' => trim($request->title), 'is_active' => true]);

        foreach ($opcionesLimpias as $texto) {
            Option::create(['survey_id' => $survey->id, 'option_text' => $texto]);
        }

        return redirect()->route('administration')->with('success', 'Encuesta creada correctamente.');
    }

    /**
     * TODO Activar / Desactivar encuesta
     */
    public function toggle(Survey $survey)
    {
        if (!$user = $this->ensureAdmin())
            return back()->with('error', 'No autorizado.');

        $survey->update(['is_active' => !$survey->is_active]);
        $estado = $survey->is_active ? 'activada' : 'desactivada';

        return back()->with('success', "La encuesta ha sido {$estado}.");
    }

    /**
     * 🗑️ Eliminar encuesta
     */
    public function destroy(Survey $survey)
    {
        if (!$user = $this->ensureAdmin())
            return back()->with('error', 'No autorizado.');

        $survey->delete();
        return back()->with('success', 'Encuesta eliminada correctamente.');
    }
}
