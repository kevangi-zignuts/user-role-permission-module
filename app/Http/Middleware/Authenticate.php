<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
//   public function handle($request, Closure $next, ...$guards)
//   {
//     // dd($guards);
//     dd(!Auth::guard($guards)->check());
//     if (!Auth::guard($guards)->check()) {
//       session(['error' => 'Trying to access unauthorized part!']);
//       return redirect()->route('login');
//     }
//     if (Auth::check() && Auth::user()->tokens()->count() === 0 && Auth::user()->email !== 'admin@example.com') {
//       session(['error' => 'You are forced logout!']);
//       return redirect()->route('login')->with('error', 'You are forced logout');
//     }
//     return $next($request);
//   }

  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    return $request->expectsJson() ? null : route('login');
  }
}
