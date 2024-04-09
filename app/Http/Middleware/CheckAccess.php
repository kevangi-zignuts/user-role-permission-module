<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!Auth::check()) {
      abort(403, 'Unauthorized access');
    }

    $adminEmail = 'admin@example.com';

    if (Auth::user()->email === $adminEmail) {
      // Admin email matches, allow access
      return $next($request);
    }
    $userRoles = Auth::user()
      ->role()
      ->pluck('role_name')
      ->toArray();

    if (empty($userRoles)) {
      abort(403, 'Unauthorized access');
    }
    return $next($request);
    // return $next($request);
  }
}
