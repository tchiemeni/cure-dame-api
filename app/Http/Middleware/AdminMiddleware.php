<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
     $user = Auth::user();

    // 🚨 Utilise la propriété 'role' qui est toujours générée 🚨
    if (Auth::check() && ($user->role === 'admin' || $user->role === 'homme_de_dieu')) {
        return $next($request);
    }

    return response()->json(['message' => 'Accès Administrateur requis.'], 403);
    }
}
