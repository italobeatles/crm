<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user, 401);

        if (! in_array($user->role, $roles, true)) {
            abort(403);
        }

        return $next($request);
    }
}
