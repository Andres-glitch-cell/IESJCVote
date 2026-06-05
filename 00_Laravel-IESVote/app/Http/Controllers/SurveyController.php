<?php

namespace App\Http\Controllers;

use App\Models\{Survey, Option, VoteRecorded};
use Illuminate\Http\Request;          // Permite manejar la información de las peticiones HTTP (inputs, formularios)
use Illuminate\Support\Facades\DB;   // Permite usar herramientas de base de datos como las transacciones (DB::transaction)
use Illuminate\Support\Arr;          // Helper de Laravel para manipulación avanzada de arrays (como Arr::wrap)
use Illuminate\Support\Str;          // Helper de Laravel para strings (como Str::random o Str::contains)

class SurveyController extends Controller
{
    // =================================─────────────────────────────────────
    // [IMPORTANT]: VISTA PÚBLICA: LISTADO DE ENCUESTAS
    // =================================─────────────────────────────────────
    public function index()
    {
        // Obtiene el rol del usuario autenticado actual (ej: 'alumno', 'profesor')
        $userRole = auth()->user()->role;

        // Consulta las encuestas que estén activas e implementa la lógica de filtrado por rol
        $surveys = Survey::where('is_active', true)
            ->where(function ($q) use ($userRole) {
                // * Verifica si la encuesta no tiene restricciones de rol (allowed_roles es null) o si el rol del usuario está incluido en el array de allowed_roles
                $q->whereNull('allowed_roles')->orWhereJsonContains('allowed_roles', $userRole);
            })
            ->with('options') // Carga las opciones relacionadas de cada encuesta para evitar consultas adicionales en la vista
            ->latest()        // Ordena los registros de manera descendente (creados más recientemente primero)
            ->get();          // Ejecuta la consulta y obtiene la colección de resultados

        // * Extrae un array simple con los IDs de las encuestas en las que el usuario ya ha votado previamente
        $votedSurveys = VoteRecorded::where('user_id', auth()->id())->pluck('survey_id')->toArray();

        // Renderiza la vista '02_surveys' enviando las dos variables necesarias mediante compact()
        return view('02_surveys', compact('surveys', 'votedSurveys'));
    }

    // =================================──────
    // [IMPORTANT]: PROCESAR VOTO (Refactorizado y masivo)
    // =======================================
    public function vote(Request $request)
    {
        // ? Lee los IDs de las opciones seleccionadas y los normaliza en un array usando Arr::wrap() para manejar tanto casos de selección única como múltiple sin errores
        $optionIds = Arr::wrap($request->input('option_ids', $request->input('option_id')));

        // Añade el array de IDs al request para poder usar el validador de Laravel.
        $request->merge(['validate_ids' => $optionIds])->validate([
            'validate_ids' => 'required|array|min:1',     // [IMPORTANT] Exige que el contenedor sea un array con al menos un elemento
            'validate_ids.*' => 'exists:options,id',       // Valida fila por fila que cada ID exista realmente en la tabla 'options'
        ]);

        // * Busca en la base de datos todos los registros de opciones correspondientes a los IDs seleccionados
        $options = Option::with('survey')->find($optionIds);
        // [IMPORTANT] De la encuesta toma el primer registro (si existe) para validar todas las opciones de esa misma encesta, y el ?-> significa que si no encuentra que no rompa la página y deje null en su lugar.
        $survey = $options->first()?->survey;

        // * Seguridad: Si no se encuentra la encuesta o las opciones pertenecen a más de una encuesta diferente, cancela el proceso
        if (!$survey || $options->pluck('survey_id')->unique()->count() > 1) {
            return redirect()->route('surveys')->with('error', 'Opciones inválidas.');
        }

        // * Anti-Trampa: Verifica si ya existe un registro de votación de este usuario en esta encuesta en específico
        if (VoteRecorded::where(['user_id' => auth()->id(), 'survey_id' => $survey->id])->exists()) {
            return redirect()->route('surveys')->with('error', 'Ya has votado en esta encuesta.');
        }

        // * Validación: Si es de selección múltiple, valida que la cantidad de IDs no supere el límite configurado en la encuesta
        if ($survey->isMultiple() && count($optionIds) > $survey->max_selections) {
            return redirect()->route('surveys')->with('error', "Máximo de {$survey->max_selections} opciones.");
        }

        // * Validación: Si la encuesta tiene categorías, verifica mediante hasDuplicates() que no haya más de una opción en la misma categoría
        if ($survey->hasCategories() && $options->pluck('category')->hasDuplicates()) {
            return redirect()->route('surveys')->with('error', 'Solo puedes seleccionar una opción por categoría.');
        }

        // * Genera un token aleatorio seguro de 32 caracteres que servirá como código de resguardo único del voto
        $codigoHash = Str::random(32);

        // [IMPORTANT]: Envuelve las operaciones de base de datos en una transacción para asegurar la integridad total de los datos
        DB::transaction(function () use ($optionIds, $survey, $codigoHash) {
            // * Se le suma 1 por que el usuario ha emitido un voto por cada opción seleccionada
            Option::whereIn('id', $optionIds)->increment('votes');
            $votes = collect($optionIds)->map(fn($id) => [
                'user_id' => auth()->id(),
                'survey_id' => $survey->id,
                'vote_hash' => $codigoHash,
                'option_id' => $id,
                'created_at' => now(),
                'updated_at' => now()
            ])->toArray();

            // ? Inserta todos los registros históricos en la tabla 'VoteRecorded' ejecutando una única sentencia SQL INSERT masiva
            VoteRecorded::insert($votes);
        });

        // * Redirecciona a la ruta del recibo inyectando los datos del comprobante en la sesión temporal (Flash Data)
        return redirect()->route('surveys.receipt')
            ->with(['codigo_resguardo' => $codigoHash, 'titulo_encuesta' => $survey->title, 'success' => '¡Voto registrado!']);
    }

    // =================================─────────────────────────────────────
    // [IMPORTANT] COMPROBANTES Y RECIBOS
    // =================================─────────────────────────────────────
    public function receipt()
    {
        // * Condicional corto (Ternario): Si la sesión contiene un código de resguardo muestra la vista, de lo contrario redirige al inicio
        if (session()->has('codigo_resguardo')) {
            return view('04_receipt');
        } else {
            return redirect()->route('surveys');
        }
    }

    public function showLastReceipt()
    {
        // ? Busca en la base de datos el último voto registrado por el usuario actual incluyendo la relación de la encuesta
        $lastVote = VoteRecorded::where('user_id', auth()->id())->with('survey')->latest()->first();

        // * Si el usuario no tiene ningún voto en su historial, aborta y lo devuelve con un mensaje de error
        if (!$lastVote) {
            return redirect()->route('surveys')->with('error', 'No se encontró ningún voto.');
        }

        // [IMPORTANT] Aqui simplemente le devuelve a la vista del recibo / resguard junto al codigo hash que debe guardar
        return view('04_receipt', ['codigo_resguardo' => $lastVote->vote_hash, 'titulo_encuesta' => $lastVote->survey->title]);
    }

    // =================================─────────────────────────────────────
    // [IMPORTANT]: PANEL DE ADMINISTRACIÓN
    // =================================─────────────────────────────────────
    public function adminIndex()
    {
        // * Retorna la vista de administración pasándole todas las encuestas con sus opciones ordenadas por fecha de creación
        return view('03_administration', ['surveys' => Survey::with('options')->latest()->get()]);
    }

    public function store(Request $request)
    {
        // ? Ejecuta el validador del formulario web para asegurar que los datos cumplen las reglas mínimas del negocio
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:single,single_cat,multiple,multiple_cat',
            'max_selections' => 'nullable|integer|min:1',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'in:alumno,profesor,padre',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
            'categories' => 'nullable|array',
        ]);

        // * Guarda un booleano (true/false) comprobando si el tipo de la encuesta contiene la palabra 'cat' (categorías)
        $hasCat = Str::contains($data['type'], 'cat');
        // * Filtra y quita espacios de las opciones eliminando cualquier espacio o caracter especial
        $opciones = array_values(array_filter(array_map('trim', $data['options'])));

        if (count($opciones) < 2)
            return back()->with('error', 'Debes incluir al menos 2 opciones.');

        // [IMPORTANT]: Validación si requiere categorías: Recorre las opciones asegurándose de que su respectiva categoría no esté vacía
        if ($hasCat) {
            foreach ($opciones as $i => $texto) {
                if (empty(trim($data['categories'][$i] ?? ''))) {
                    return back()->with('error', "La opción \"{$texto}\" necesita una categoría.");
                }
            }
        }

        // [IMPORTANT]: 1. Si el tipo de encuesta es de selección múltiple, asigna el valor de max_selections, de lo contrario lo fija en 1 (selección única)
        if (Str::contains($data['type'], 'multiple')) {
            $maxSelecciones = max(1, (int) $data['max_selections']);
        } else {
            $maxSelecciones = 1;
        }

        // 2. Insertamos la encuesta principal utilizando la variable que calculamos arriba
        $survey = Survey::create([
            'title' => trim($data['title']),
            'type' => $data['type'],
            'max_selections' => $maxSelecciones, // * Asigna el resultado del IF de arriba
            'allowed_roles' => $data['allowed_roles'] ?? null, // * Si viene nulo guarda null (abierto a todos los roles)
            'is_active' => true, // * La encuesta se crea activa por defecto
        ]);

        // # 3. Transformamos el array de textos limpios en filas preparadas para la tabla 'options'
        $optionsData = collect($opciones)->map(function ($texto, $i) use ($survey, $hasCat, $data) {

            if ($hasCat) {
                // Si la encuesta usa categorías, limpia los espacios del texto enviado
                $categoriaFinal = trim($data['categories'][$i]);
            } else {
                // Si no usa categorías, lo dejamos vacío como null
                $categoriaFinal = null;
            }

            return [
                'survey_id' => $survey->id,   // Vincula la opción al ID de la encuesta recién creada
                'option_text' => $texto,      // Texto descriptivo de la opción
                'category' => $categoriaFinal, // Asigna el resultado del IF de la categoría
                'created_at' => now(),        // Marca de tiempo de creación actual
                'updated_at' => now()         // Marca de tiempo de actualización actual
            ];
        })->toArray(); // * Convierte toda la colección final en un array normal de PHP

        // [IMPORTANT]: Registra todas las opciones creadas en la tabla 'options' en una sola consulta de inserción masiva a la base de datos
        Option::insert($optionsData);

        // * Redirecciona al panel de control enviando en sesión el título de la encuesta para confirmar el éxito del guardado
        return redirect()->route('admin.dashboard')->with('success', $survey->title);
    }

    // ! NO FUNCIONAL !
    public function toggle(Survey $survey)
    {
        // Cambia el estado de la columna 'is_active' al opuesto de su estado actual (Invierte true/false)
        $survey->update(['is_active' => !$survey->is_active]);
        // Regresa a la vista previa inyectando un mensaje que cambia dinámicamente según el nuevo estado de la encuesta
        return back()->with('success_message', "La encuesta ha sido " . ($survey->is_active ? 'activada.' : 'desactivada.'));
    }

    public function destroy(Survey $survey)
    {
        // Ejecuta el borrado del registro seleccionado de la base de datos usando Eloquent de Laravel
        $survey->delete();
        // Redirecciona al panel administrativo enviando una bandera para disparar una alerta de eliminación en la interfaz
        return redirect()->route('admin.dashboard')->with('deleted', 'true');
    }

    // ! NO FUNCIONAL !
    public function results(Survey $survey)
    {
        // Retorna la pantalla administrativa de estadísticas cargando bajo demanda (lazy eager loading) sus opciones correspondientes
        return view('admin.surveys.results', ['survey' => $survey->load('options')]);
    }

    public function export(Survey $survey)
    {
        // Retorna a la página anterior con una alerta temporal indicando que la lógica de exportación está por implementarse
        return back()->with('error', 'Exportación en desarrollo.');
    }
}
