<?php

use Illuminate\Support\Facades\Route; // [IMPORTANT] Permite usar el enrutador de Laravel (Route::get, Route::post, etc.)
use Illuminate\Http\Request;         // [IMPORTANT] Permite capturar y procesar los datos que envía el usuario (formularios, inputs)
use App\Models\User;                  // [IMPORTANT] Importa el modelo User para interactuar con la tabla 'users' de la Base de Datos


// ** BLOCK 1
/**
 ** PROPÓSITO: Cargar el formulario de registro por primera vez al entrar a la web.
 **/
Route::get('/', function () {
    return view('00_register');
})->name('home');

// HACK: Regla de oro de los navegadores: Cuando un usuario refresca la página manualmente con F5, el navegador NUNCA vuelve a enviar un POST (a menos que confirme un mensaje de reenvío de formulario); el navegador siempre intenta recargar esa misma URL (/register) usando el método GET.

// ** BLOCK 1
/**
 ** PROPÓSITO: Si el usuario recarga la página manualmente, se le vuelve a mostrar el formulario limpio.
 **/
Route::get('/register', function () {
    // SAFE: Si acceden por GET a /register, simplemente les volvemos a pintar el mismo formulario
    return view('00_register');
});

// ** BLOCK 2
/**
 ** PROPÓSITO: Validar los campos, registrar al usuario en la BD e iniciar su sesión.
 **/
Route::post('/register', function (Request $request) {

    // Captura el input 'password_admin'. Si viene vacío, por defecto asigna una cadena vacía ''.
    // trim() elimina los espacios en blanco que el usuario pueda meter sin querer al principio o al final.
    $passwordAdmin = trim($request->input('password_admin', ''));

    // VALIDACIÓN BÁSICA DE CONTROL:
    // El símbolo '!' significa "NO". Si NO viene el 'nombre' O (||) NO viene el 'dni'...
    if (!$request->nombre || !$request->dni) {
        // Redirige al usuario de vuelta a la raíz de la web...
        return redirect()->route('home')
            // ... y guarda en la sesión un mensaje temporal de error que puedes pintar en el HTML
            ->with('error', 'Faltan datos obligatorios');
    }

    // INSERCIÓN EN BASE DE DATOS:
    // Llama al modelo User para insertar una nueva fila en la tabla mediante asignación masiva
    $user = User::create([
        'name' => $request->nombre, // Guarda el campo 'nombre' del formulario en la columna 'name'
        'dni' => $request->dni,    // Guarda el campo 'dni' del formulario en la columna 'dni'

        // EVALUACIÓN BOOLEANA:
        // Compara si la contraseña escrita es exactamente "IESJCVote2026".
        // Si coincide, guarda un 'true' (1) en la columna 'is_admin'. Si no coincide, guarda un 'false' (0).
        'is_admin' => $passwordAdmin === "IESJCVote2026"
    ]);

    // LOGIN MANUAL SIMPLE:
    // Almacena el ID del usuario recién creado en la sesión del navegador bajo la clave 'user_id'.
    // Esto sirve para saber qué usuario está navegando por la web en las siguientes páginas.
    session(['user_id' => $user->id]);

    // REDIRECCIÓN INTELIGENTE SEGÚN EL ROL:
    // Evaluamos el campo 'is_admin' que acabamos de meter en el objeto $user
    if ($user->is_admin) {
        // Si es verdadero (admin), lo mandamos directamente al panel de administración
        return redirect()->route('admin.dashboard');
    }

    // Si la condición de arriba no se cumple (es un votante común), salta aquí y lo manda a las encuestas
    return redirect()->route('surveys.index');

})->name('register'); // Le asigna el apodo/nombre 'register' (Usado en el action="{{ route('register') }}" del HTML)


// =========================================================================
// BLOQUE 3: RUTAS AUXILIARES Y PANELES DE USUARIO (MÉTODO GET)
// =========================================================================

/**
 * RUTA: El login alternativo solicitado en tu footer (http://127.0.0.1:8000/login)
 * MÉTODO: GET
 * PROPÓSITO: Evitar el error "Route [login] not defined" al renderizar el enlace del footer del formulario.
 */
Route::get('/login', function () {
    return view('00_register'); // Actualmente reutiliza la vista del registro
})->name('login');

/**
 * RUTA: Panel de Encuestas para Votantes (http://127.0.0.1:8000/surveys)
 * MÉTODO: GET
 * PROPÓSITO: Mostrar el listado de votaciones disponibles si el usuario está autenticado.
 */
Route::get('/surveys', function () {

    // CONTROL DE ACCESO (Mínima seguridad):
    // Si la sesión NO contiene la clave 'user_id' (significa que es un invitado que intenta colarse escribiendo la URL)...
    if (!session()->has('user_id')) {
        // Lo expulsa mandándolo al home con un mensaje de advertencia
        return redirect()->route('home')->with('error', 'Debe registrarse primero.');
    }

    // Si superó el control de acceso, le muestra la vista '01_surveys.blade.php'
    return view('01_surveys');
})->name('surveys.index');

/**
 * RUTA: Panel de Administración (http://127.0.0.1:8000/administration)
 * MÉTODO: GET
 * PROPÓSITO: Mostrar las opciones de gestión electoral únicamente si el usuario es administrador.
 */
Route::get('/administration', function () {

    // PRIMER FILTRO: ¿Está logueado?
    // Si no hay ningún 'user_id' en la sesión del navegador...
    if (!session()->has('user_id')) {
        // Lo expulsa directamente al formulario inicial sin explicaciones
        return redirect()->route('home');
    }

    // SEGUNDO FILTRO: ¿Es realmente un administrador?
    // Busca en la base de datos al usuario usando el ID que guardamos en la sesión
    $user = User::find(session('user_id'));

    // Si el usuario no existe en la BD (por ejemplo, fue borrado) O (||) su columna 'is_admin' es falsa...
    if (!$user || !$user->is_admin) {
        // Lo desvía al panel de encuestas normales y le avisa que no tiene permisos de administrador
        return redirect()->route('surveys.index')->with('error', 'Acceso no autorizado.');
    }

    // Si pasa ambos filtros con éxito, le carga la pantalla de administración '02_administration.blade.php'
    return view('02_administration');
})->name('admin.dashboard');
