<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Разрешает ходить дальше только admin|superadmin.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Доступ запрещён');
        }

        return $next($request);
    }
}
