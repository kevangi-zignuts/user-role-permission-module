<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
  public function handle($request, Closure $next, ...$guards)
  {
    if (!Auth::guard($guards)->check()) {
      return $this->redirectTo($request);
    }
    if (
      Auth::check() &&
      Auth::user()
        ->tokens()
        ->count() === 0 &&
      Auth::user()->email !== 'admin@example.com'
    ) {
      // No tokens associated with the user
      return redirect()->route('login');
    }
    return $next($request);
  }

  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    return $request->expectsJson() ? null : route('login');
  }
}
