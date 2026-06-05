<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Option;
use App\Models\VoteRecorded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    // ──────────────────────────────────────────────────────────────────────
    // VISTA PÚBLICA: listado de encuestas activas filtradas por rol
    // ──────────────────────────────────────────────────────────────────────
    public function index()
    {
        $userRole = auth()->user()->role;

        // Solo mostramos encuestas activas que permitan el rol del usuario
        // Si allowed_roles es null, la encuesta es visible para todos
        $surveys = Survey::where('is_active', true)
            ->where(function ($query) use ($userRole) {
                $query->whereNull('allowed_roles')
                    ->orWhereJsonContains('allowed_roles', $userRole);
            })
            ->with('options')
            ->latest()
            ->get();

        $votedSurveys = VoteRecorded::where('user_id', auth()->id())
            ->pluck('survey_id')
            ->toArray();

        return view('02_surveys', compact('surveys', 'votedSurveys'));
    }

    // ──────────────────────────────────────────────────────────────────────
    // PROCESAR VOTO
    // ──────────────────────────────────────────────────────────────────────
    public function vote(Request $request)
    {
        $isSingle = $request->has('option_id');

        if ($isSingle) {
            $request->validate(['option_id' => 'required|exists:options,id']);
            $optionIds = [$request->option_id];
        } else {
            $request->validate([
                'option_ids' => 'required|array|min:1',
                'option_ids.*' => 'exists:options,id',
            ]);
            $optionIds = $request->option_ids;
        }

        $firstOption = Option::with('survey')->findOrFail($optionIds[0]);
        $survey = $firstOption->survey;
        $userId = auth()->id();

        // Verificamos que todas las opciones pertenecen a la misma encuesta
        $allBelongToSurvey = Option::whereIn('id', $optionIds)
            ->where('survey_id', $survey->id)
            ->count() === count($optionIds);

        if (!$allBelongToSurvey) {
            return redirect()->route('surveys')->with('error', 'Opciones inválidas.');
        }

        // Verificamos que el usuario no ha votado ya
        $exists = VoteRecorded::where('user_id', $userId)
            ->where('survey_id', $survey->id)
            ->exists();

        if ($exists) {
            return redirect()->route('surveys')->with('error', 'Ya has votado en esta encuesta.');
        }

        // Verificamos límite de selecciones para tipos múltiples
        if ($survey->isMultiple() && count($optionIds) > $survey->max_selections) {
            return redirect()->route('surveys')
                ->with('error', "Solo puedes seleccionar un máximo de {$survey->max_selections} opciones.");
        }

        // Verificamos 1 opción por categoría para tipos con categorías
        if ($survey->hasCategories()) {
            $categoriesSelected = Option::whereIn('id', $optionIds)->pluck('category')->toArray();
            if (count($categoriesSelected) !== count(array_unique($categoriesSelected))) {
                return redirect()->route('surveys')
                    ->with('error', 'Solo puedes seleccionar una opción por categoría.');
            }
        }

        // Procesamos el voto en una transacción
        $codigoHash = bin2hex(random_bytes(16));

        DB::transaction(function () use ($optionIds, $userId, $survey, $codigoHash) {
            Option::whereIn('id', $optionIds)->increment('votes');

            foreach ($optionIds as $id) {
                VoteRecorded::create([
                    'user_id' => $userId,
                    'survey_id' => $survey->id,
                    'vote_hash' => $codigoHash,
                    'option_id' => $id,
                ]);
            }
        });

        return redirect()->route('surveys.receipt')
            ->with('codigo_resguardo', $codigoHash)
            ->with('titulo_encuesta', $survey->title)
            ->with('success', '¡Voto registrado correctamente!');
    }

    // ──────────────────────────────────────────────────────────────────────
    // RESGUARDO
    // ──────────────────────────────────────────────────────────────────────
    public function receipt()
    {
        if (!session()->has('codigo_resguardo'))
            return redirect()->route('surveys');
        return view('04_receipt');
    }

    public function showLastReceipt()
    {
        $lastVote = VoteRecorded::where('user_id', auth()->id())
            ->with('survey')
            ->latest()
            ->first();

        if (!$lastVote)
            return redirect()->route('surveys')->with('error', 'No se encontró ningún voto.');

        return view('04_receipt', [
            'codigo_resguardo' => $lastVote->vote_hash,
            'titulo_encuesta' => $lastVote->survey->title,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    // PANEL DE ADMINISTRACIÓN
    // ──────────────────────────────────────────────────────────────────────
    public function adminIndex()
    {
        $surveys = Survey::with('options')->latest()->get();
        return view('03_administration', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:single,single_cat,multiple,multiple_cat',
            'max_selections' => 'nullable|integer|min:1',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'in:alumno,profesor,padre',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string|max:255',
        ]);

        $type = $request->type;
        $hasCategories = in_array($type, ['single_cat', 'multiple_cat']);
        $isMultiple = in_array($type, ['multiple', 'multiple_cat']);
        $maxSelections = $isMultiple ? max(1, (int) $request->max_selections) : 1;
        $opciones = array_values(array_filter(array_map('trim', $request->options)));
        $categorias = $request->categories ?? [];

        // Si no se selecciona ningún rol, la encuesta es visible para todos
        $allowedRoles = $request->allowed_roles ?? null;

        if (count($opciones) < 2)
            return back()->with('error', 'Debes incluir al menos 2 opciones.');

        if ($hasCategories) {
            foreach ($opciones as $i => $texto) {
                if (empty(trim($categorias[$i] ?? '')))
                    return back()->with('error', "La opción \"{$texto}\" necesita una categoría.");
            }
        }

        $survey = Survey::create([
            'title' => trim($request->title),
            'type' => $type,
            'max_selections' => $maxSelections,
            'allowed_roles' => $allowedRoles,
            'is_active' => true,
        ]);

        foreach ($opciones as $i => $texto) {
            Option::create([
                'survey_id' => $survey->id,
                'option_text' => $texto,
                'category' => $hasCategories ? trim($categorias[$i] ?? '') : null,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', $survey->title);
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
        return redirect()->route('admin.dashboard')->with('deleted', 'true');
    }

    public function results(Survey $survey)
    {
        $survey->load('options');
        return view('admin.surveys.results', compact('survey'));
    }

    public function export(Survey $survey)
    {
        // TODO: implementar exportación
        return back()->with('error', 'Exportación en desarrollo.');
    }
}
