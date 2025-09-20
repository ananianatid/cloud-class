<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictTeacherAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est un enseignant, ajouter des restrictions
        if ($user && $user->role === 'enseignant') {
            // Stocker l'enseignant dans la session pour utilisation dans les ressources
            $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();
            if ($enseignant) {
                session(['current_enseignant_id' => $enseignant->id]);
            }
        }

        return $next($request);
    }
}
