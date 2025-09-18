<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $requiredRole)
    {
        $user = $request->user();

        if (!$user || $user->role !== $requiredRole) {
            throw new HttpException(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}

