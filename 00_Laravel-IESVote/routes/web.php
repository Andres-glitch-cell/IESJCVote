<?php

use Illuminate\Support\Facades\Route;       // Permite usar el enrutador de Laravel
use Illuminate\Http\Request;               // Permite capturar y procesar los datos que envía el usuario
use App\Models\User;                        // Importa el modelo User para interactuar con la tabla 'users'
use App\Models\Survey;                      // Importa el modelo de Encuestas
use App\Models\Option;                      // Importa el modelo de Opciones
use App\Models\VoteRecorded;                // Importa el modelo de Registro de Votos Emitidos (Puntos 2 y 7)
use Illuminate\Support\Facades\Hash;       // Requerido para encriptar/hashear contraseñas
use App\Http\Controllers\LoginController;   // Importación necesaria para el endpoint de verificación asíncrona

// =========================================================================
// BLOQUE 1: PORTAL ELECTOR (VISTAS DE ACCESO - MÉTODO GET)
// =========================================================================

/**
 ** PROPÓSITO: Cargar la pantalla principal de login en la raíz del proyecto.
 **/
Route::get('/', function () {
    return view('01_login');
})->name('home');

/**
 ** PROPÓSITO: Cargar la pantalla de login si se accede explícitamente a /login.
 **/
Route::get('/login', function () {
    return view('01_login');
})->name('login');

/**
 ** PROPÓSITO: Cargar la pantalla de registro independiente.
 **/
Route::get('/register', function () {
    return view('00_register');
})->name('register');


// =========================================================================
// BLOQUE 2: PROCESAMIENTO DE FORMULARIOS (PETICIONES POST)
// =========================================================================

/**
 ** PROPÓSITO: Validar los campos, registrar al usuario común en la BD y redirigir al login.
 **/
Route::post('/register', function (Request $request) {

    // VALIDACIÓN BÁSICA DE CONTROL:
    if (!$request->nombre || !$request->dni) {
        return redirect()->route('register')
            ->with('error', 'Faltan datos obligatorios')
            ->withInput();
    }

    // CONTROL DE DUPLICADOS: Evita que falle el servidor si el DNI ya existe en la BD
    $dniLimpio = strtoupper(trim($request->dni));
    if (User::where('dni', $dniLimpio)->exists()) {
        return redirect()->route('register')->with('error', 'Este DNI ya está registrado.');
    }

    // INSERCIÓN EN BASE DE DATOS SEGURA:
    User::create([
        'name' => trim($request->nombre),
        'dni' => $dniLimpio,
        'is_admin' => false,
        'password' => Hash::make($dniLimpio)
    ]);

    return redirect()->route('login')->with('success', 'Registro completado. ¡Inicie sesión para votar!');

})->name('register.post');

/**
 ** PROPÓSITO: Procesar la autenticación de usuarios y desvío inteligente de vistas.
 **/
Route::post('/login', function (Request $request) {

    $passwordAdmin = trim($request->input('password_admin', ''));

    // CONTROL DE CAMPOS OBLIGATORIOS:
    if (!$request->nombre || !$request->dni) {
        return redirect()->route('login')->with('error', 'Por favor, rellene el nombre y el DNI.')->withInput();
    }

    $dniLimpio = strtoupper(trim($request->dni));

    // BÚSQUEDA DE USUARIO POR DNI:
    $user = User::where('dni', $dniLimpio)->first();

    if (!$user) {
        return redirect()->route('login')->with('error', 'El DNI introducido no está registrado.')->withInput();
    }

    // CONTROL DE INTEGRIDAD: Validar que el nombre coincida con el DNI registrado
    if (strcasecmp($user->name, trim($request->nombre)) !== 0) {
        return redirect()->route('login')->with('error', 'El nombre no coincide con el DNI proporcionado.')->withInput();
    }

    // LOGIN CON ÉXITO: Guardamos el identificador del usuario en la sesión global
    session(['user_id' => $user->id]);

    // [FLUJO CORREGIDO]: EVALUACIÓN DE CONTRASEÑA DE ADMINISTRADOR
    if ($passwordAdmin === "IESJCVote2026") {
        // Si introduce la contraseña correcta, escala a administrador y entra al panel de gestión
        $user->update(['is_admin' => true]);
        return redirect()->route('administration');
    }

    // Si el usuario ya era administrador previo pero NO introdujo la contraseña en este login,
    // o simplemente es un elector normal sin clave, lo enviamos directamente a la vista de las encuestas para votar.
    return redirect()->route('surveys');

})->name('login.post');


// =========================================================================
// BLOQUE 3: PANELES INTERNOS DE USUARIO Y PROTECCIÓN POR SESIÓN (MÉTODO GET Y POST)
// =========================================================================

/**
 * RUTA: Panel de Encuestas para Votantes (Vista donde aparece la encuesta)
 */
Route::get('/surveys', function () {
    if (!session()->has('user_id')) {
        return redirect()->route('home')->with('error', 'Debe registrarse primero.');
    }

    // Recuperamos todas las encuestas junto con sus opciones vinculadas de la BD
    $surveys = Survey::with('options')->latest()->get();

    // Pasamos la variable $surveys a la vista usando compact()
    return view('02_surveys', compact('surveys'));
})->name('surveys');

/**
 * RUTA: Procesar el voto emitido por el elector (POST) - Utilizando VoteRecorded
 */
Route::post('/surveys', function (Request $request) {
    // 1. Control de seguridad por sesión
    if (!session()->has('user_id')) {
        return redirect()->route('home')->with('error', 'Debe registrarse primero.');
    }

    $userId = session('user_id');

    // 2. Validar selección del input radio
    if (!$request->has('survey_option')) {
        return redirect()->route('surveys')->with('error', 'Por favor, selecciona una opción antes de votar.');
    }

    $optionId = $request->input('survey_option');

    // 3. Obtener la opción y su encuesta asociada de forma ansiosa
    $option = Option::with('survey')->find($optionId);
    if (!$option) {
        return redirect()->route('surveys')->with('error', 'La opción seleccionada no es válida.');
    }

    $surveyId = $option->survey_id;

    // 4. CONTROL DE DUPLICADOS (Punto 2 del enunciado: Un solo voto por persona y proceso)
    $yaHaVotado = VoteRecorded::where('user_id', $userId)
        ->where('survey_id', $surveyId)
        ->exists();

    if ($yaHaVotado) {
        return redirect()->route('surveys')->with('error', 'Ya has registrado tu participación en la votación: "' . $option->survey->title . '".');
    }

    // 5. ENCRIPCION / GENERACIÓN DE IDENTIFICADOR ÚNICO (Punto 7 del enunciado)
    $semillaCriptografica = $userId . '-' . $surveyId . '-' . $optionId . '-' . microtime(true);
    $votoHash = hash('sha256', $semillaCriptografica);

    // 6. GUARDAR PARTICIPACIÓN EN LA TABLA VOTE_RECORDEDS E INCREMENTAR EL VOTO
    VoteRecorded::create([
        'user_id' => $userId,
        'survey_id' => $surveyId,
        'vote_hash' => $votoHash
    ]);

    if (\Schema::hasColumn('options', 'votes')) {
        $option->increment('votes');
    }

    // 7. REDIRECCIÓN CON SESIÓN FLASH HACIA LA VISTA DE RESGUARDO (Punto 7)
    return redirect()->route('surveys.receipt')->with([
        'success_vote' => true,
        'titulo_encuesta' => $option->survey->title,
        'codigo_resguardo' => $votoHash
    ]);
})->name('surveys.vote');

/**
 * RUTA: Pantalla Intermedia de Justificante / Resguardo de Voto (GET)
 */
Route::get('/surveys/receipt', function () {
    if (!session()->has('user_id') || !session()->has('codigo_resguardo')) {
        return redirect()->route('surveys');
    }
    return view('04_receipt');
})->name('surveys.receipt');

/**
 * RUTA: Panel de Administración
 */
Route::get('/administration', function () {
    if (!session()->has('user_id')) {
        return redirect()->route('home');
    }

    $user = User::find(session('user_id'));

    if (!$user || !$user->is_admin) {
        return redirect()->route('surveys')->with('error', 'Acceso no autorizado.');
    }

    return view('03_administration');
})->name('administration');

/**
 * RUTA: Endpoint asíncrono para comprobar el censo electoral desde el login.
 */
Route::post('/login/verificar-elector', [LoginController::class, 'verificarElector'])->name('login.verificar');

/**
 * RUTA: Procesar la creación de una nueva encuesta dinámica.
 */
Route::post('/administration/survey/store', function (Request $request) {

    if (!session()->has('user_id')) {
        return redirect()->route('home');
    }

    $user = User::find(session('user_id'));
    if (!$user || !$user->is_admin) {
        return redirect()->route('surveys')->with('error', 'No autorizado.');
    }

    if (!$request->title || !$request->options || !is_array($request->options)) {
        return redirect()->route('administration')->with('error', 'Faltan datos para crear la encuesta.');
    }

    $opcionesLimpias = array_filter(array_map('trim', $request->options));

    if (count($opcionesLimpias) < 2) {
        return redirect()->route('administration')->with('error', 'Una votación requiere al menos 2 opciones válidas.');
    }

    $survey = Survey::create([
        'title' => trim($request->title)
    ]);

    foreach ($opcionesLimpias as $textoOpcion) {
        Option::create([
            'survey_id' => $survey->id,
            'option_text' => $textoOpcion
        ]);
    }

    return redirect()->route('administration')->with('success', '¡Nueva encuesta electoral publicada con éxito!');
})->name('surveys.store');

// =========================================================================
// BLOQUE 3: PANELES INTERNOS Y PROTECCIÓN (MÉTODO GET Y POST)
// =========================================================================

/**
 * RUTA: Panel de Encuestas
 */
Route::get('/surveys', function () {
    if (!session()->has('user_id')) {
        return redirect()->route('home')->with('error', 'Debe registrarse primero.');
    }
    $surveys = Survey::with('options')->latest()->get();
    return view('02_surveys', compact('surveys'));
})->name('surveys');

/**
 * RUTA: Procesar el voto (POST)
 */
Route::post('/surveys', function (Request $request) {
    if (!session()->has('user_id'))
        return redirect()->route('home');

    $userId = session('user_id');
    $option = Option::with('survey')->find($request->input('survey_option'));

    if (!$option)
        return redirect()->route('surveys')->with('error', 'Opción no válida.');

    $surveyId = $option->survey_id;

    if (VoteRecorded::where('user_id', $userId)->where('survey_id', $surveyId)->exists()) {
        return redirect()->route('surveys')->with('error', 'Ya has votado en este proceso.');
    }

    $votoHash = hash('sha256', $userId . '-' . $surveyId . '-' . microtime(true));

    VoteRecorded::create([
        'user_id' => $userId,
        'survey_id' => $surveyId,
        'vote_hash' => $votoHash
    ]);

    // Redirección con sesión flash
    return redirect()->route('surveys.receipt')->with([
        'codigo_resguardo' => $votoHash,
        'titulo_encuesta' => $option->survey->title
    ]);
})->name('surveys.vote');

/**
 * RUTA: Pantalla de Resguardo (Mejorada para persistencia)
 */
Route::get('/surveys/receipt', function () {
    if (!session()->has('user_id'))
        return redirect()->route('home');

    // Si la sesión flash ha caducado, buscamos el último voto en la BD
    if (!session()->has('codigo_resguardo')) {
        $ultimoVoto = VoteRecorded::where('user_id', session('user_id'))->latest()->first();

        if ($ultimoVoto) {
            $encuesta = Survey::find($ultimoVoto->survey_id);
            // Re-inyectamos los datos necesarios para la vista
            session([
                'codigo_resguardo' => $ultimoVoto->vote_hash,
                'titulo_encuesta' => $encuesta ? $encuesta->title : 'Consulta Electoral'
            ]);
        } else {
            return redirect()->route('surveys')->with('error', 'No se encontró ningún voto reciente.');
        }
    }
    return view('04_receipt');
})->name('surveys.receipt');
?>