<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json(['message' => 'Usuario no autenticado'], 401);
            }
            
            if ($user->role !== $role) {
                return response()->json(['message' => 'No tienes permiso para acceder a esta ruta', 'user_role' => $user->role, 'required_role' => $role], 403);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error en middleware de roles: ' . $e->getMessage()], 500);
        }
    }
}
