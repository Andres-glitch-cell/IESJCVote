<?php

namespace App\Http\Middleware; // ? Define la carpeta/espacio de nombres donde se guarda este archivo de seguridad

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request; // * Importa la herramienta para analizar la petición actual del usuario (url, datos, etc.)

class Authenticate extends Middleware
{
    /**
     * * Determina a qué URL se debe redirigir al usuario cuando no ha iniciado sesión.
     */
    /**
     * Determina a qué URL se debe redirigir al usuario cuando no ha iniciado sesión.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Si viene de un usuario normal en su navegador, lo mandamos a la pantalla de login.
        if ($request->expectsJson()) {
            // [IMPORTANT] Si es una petición de datos (API/AJAX). Devolvemos vacío para que salte un error 401.
            return null;
        } else {
            // [IMPORTANT] Camino del NO: Es una persona navegando en la web. Lo mandamos al formulario de inicio de sesión.
            return route('login');
        }
    }
}
