<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, $moduleCode, $accessType): Response
  {
    if (!Auth::check()) {
      return redirect()->route('login');
    }
    if (!Auth::user()->hasPermission($moduleCode, $accessType)) {
      return abort(403, 'Unauthorized'); // or redirect to another page
    }
    $module = Module::findOrFail($moduleCode);
    if (
      $module->parent_code !== null &&
      !(
        Auth::user()->hasPermission($module->parent_code, 'add_access') ||
        Auth::user()->hasPermission($module->parent_code, 'view_access')
      )
    ) {
      return abort(403, 'Unauthorized');
    }
    return $next($request);
  }
}
