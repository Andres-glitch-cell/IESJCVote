<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // NOTE: Comprobamos si el usuario está autenticado Y si es admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // [IMPORTANT] Si no es admin, lo echamos al inicio con un mensaje de error
        return redirect('/')->with('error', 'Acceso denegado: Se requieren privilegios de administrador.');
    }
}