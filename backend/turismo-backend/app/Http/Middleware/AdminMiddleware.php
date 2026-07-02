<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\AuthenticationException; // Importar la excepción de autenticación

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario no está autenticado, el middleware 'auth:sanctum' debería haberlo manejado.
        // Si llegamos aquí y no hay usuario, algo está mal o la ruta no está protegida correctamente.
        // Sin embargo, para este middleware, asumimos que 'auth:sanctum' ya se ejecutó.
        // Si el usuario no existe, lanzamos una excepción de autenticación para que Laravel la maneje como 401.
        if (!$request->user()) {
            throw new AuthenticationException('Unauthenticated.');
        }

        // Si el usuario existe, verificamos su rol
        if ($request->user()->rol === 'admin') {
            return $next($request);
        }

        // Si el usuario está autenticado pero no es admin, denegamos el acceso
        return response()->json(['message' => 'Unauthorized: Admin access required'], 403);
    }
}
