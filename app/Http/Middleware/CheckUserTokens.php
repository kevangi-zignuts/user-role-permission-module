<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserTokens
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (
      Auth::check() &&
      Auth::user()
        ->tokens()
        ->count() === 0 &&
      Auth::id() !== 1
    ) {
      // No tokens associated with the user
      return redirect()->route('login');
    }
    return $next($request);
  }
}
