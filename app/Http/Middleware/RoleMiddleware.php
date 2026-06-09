<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
       public function handle(Request $request, Closure $next, ...$roles)
       {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Periksa apakah role user terdaftar dalam parameter route middleware
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
