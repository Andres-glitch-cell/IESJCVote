<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Option;
use App\Models\VoteRecorded;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('is_active', true)
            ->with('options')
            ->latest()
            ->get();

        $votedSurveys = VoteRecorded::where('user_id', auth()->id())
            ->pluck('survey_id')
            ->toArray();

        return view('02_surveys', compact('surveys', 'votedSurveys'));
    }

    public function vote(Request $request)
    {
        $request->validate(['option_id' => 'required|exists:options,id']);
        $option = Option::with('survey')->findOrFail($request->option_id);
        $userId = auth()->id();

        $exists = VoteRecorded::where('user_id', $userId)
            ->where('survey_id', $option->survey_id)
            ->exists();

        if ($exists) {
            return redirect()->route('surveys')->with('error', 'Ya has votado en esta encuesta.');
        }

        $codigoHash = bin2hex(random_bytes(16));
        $option->increment('votes_count');

        VoteRecorded::create([
            'user_id' => $userId,
            'survey_id' => $option->survey_id,
            'vote_hash' => $codigoHash
        ]);

        return redirect()->route('surveys.receipt')
            ->with('codigo_resguardo', $codigoHash)
            ->with('titulo_encuesta', $option->survey->title)
            ->with('success', '¡Voto registrado correctamente!');
    }

    public function receipt()
    {
        if (!session()->has('codigo_resguardo'))
            return redirect()->route('surveys');
        return view('04_receipt');
    }

    public function adminIndex()
    {
        $surveys = Survey::with('options')->latest()->get();
        return view('03_administration', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $titulo = trim($request->title);
        $opcionesLimpias = array_filter(array_map('trim', $request->options));

        if (count($opcionesLimpias) < 2)
            return back()->with('error', 'Debes incluir al menos 2 opciones.');

        $survey = Survey::create(['title' => $titulo, 'is_active' => true]);

        foreach ($opcionesLimpias as $texto) {
            Option::create(['survey_id' => $survey->id, 'option_text' => $texto]);
        }

        return redirect()->route('administration')->with('success', $titulo);
    }

    public function toggle(Survey $survey)
    {
        $survey->update(['is_active' => !$survey->is_active]);
        $estado = $survey->is_active ? 'activada' : 'desactivada';
        return back()->with('success_message', "La encuesta ha sido {$estado}.");
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();
        return redirect()->route('administration')->with('deleted', 'true');
    }

    public function showLastReceipt()
    {
        $lastVote = VoteRecorded::where('user_id', auth()->id())->with('survey')->latest()->first();

        if (!$lastVote)
            return redirect()->route('surveys')->with('error', 'No se encontró ningún voto.');

        return view('04_receipt', [
            'codigo_resguardo' => $lastVote->vote_hash,
            'titulo_encuesta' => $lastVote->survey->title
        ]);
    }
}