<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$checkRole): Response
    {
        if (auth()->guard('admins')->check()) {
            $role = $checkRole;

            $currentUserRole = auth()->user()->role;

            if (in_array($currentUserRole, $role)) {
                return $next($request);
            }

        }

        return redirect()->to("/login");
    }
}
