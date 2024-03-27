<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    return $request->expectsJson() ? null : route('login');
  }

  public function handle($request, Closure $next, ...$guards)
  {
    $this->authenticate($request, $guards);

    // Ensure that $guards is an array before attempting to count it
    if (count($guards) > 0) {
      foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
          // Check if the user is authenticated and flagged for forced logout
          if (Auth::user()->forced_logout) {
            Auth::logout();
            return redirect()
              ->route('login')
              ->with('error', 'You have been logged out due to security reasons.');
          }
        }
      }
    }

    return $next($request);
  }
}
