<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminChecked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::user()->email === 'admin@example.com') {
                return $next($request);
            }

            return redirect()->route('user.dashboard')->with('error', 'Trying to access unauthorized part');
        }

        return redirect()->route('auth-login-basic')->with('error', 'Trying to access unauthorized part');
    }
}
