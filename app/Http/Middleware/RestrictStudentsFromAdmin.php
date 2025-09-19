<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictStudentsFromAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si l'utilisateur est connecté et a le rôle 'etudiant', afficher une page d'erreur
        if ($user && $user->role === 'etudiant') {
            return response()->view('errors.403-student', [], 403);
        }

        return $next($request);
    }
}
