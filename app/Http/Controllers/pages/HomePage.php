<?php

namespace App\Http\Controllers\pages;

use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomePage extends Controller
{
  public function index()
  {
    if (
      Auth::check() &&
      Auth::user()
        ->tokens()
        ->count() === 0 &&
      Auth::user()->id !== 1
    ) {
      // No tokens associated with the user
      return redirect()->route('login');
    }
    $module_count = Module::where('is_active', 1)->count();
    $permission_count = Permission::where('is_active', 1)->count();
    $role_count = Role::where('is_active', 1)->count();
    $user_count = User::where('is_active', 1)->count();
    // dd($module_count);
    return view('content.pages.pages-home', compact('module_count', 'permission_count', 'role_count', 'user_count'));
  }
}
