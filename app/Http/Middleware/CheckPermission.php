<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
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
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        if (! Auth::user()->hasPermission($moduleCode, $accessType)) {
            session(['error' => 'Unauthorized Access']);
            return redirect()->back();
        }
        $module = Module::findOrFail($moduleCode);
        if ($module->parent_code !== null && ! Auth::user()->hasPermission($module->parent_code, 'view_access')) {
            session(['error' => 'Unauthorized Access']);
            return redirect()->back();
        }

        return $next($request);
    }
}
