<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Allow access to the change password route and logout
            if ($request->routeIs('password.change.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            return redirect()->route('password.change.show');
        }

        return $next($request);
    }
}
