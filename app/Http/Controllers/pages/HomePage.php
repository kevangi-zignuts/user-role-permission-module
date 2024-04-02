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
  /**
   * show a dashboard for admin side
   */
  public function index()
  {
    $module_count = Module::where('is_active', 1)->count();
    $permission_count = Permission::where('is_active', 1)->count();
    $role_count = Role::where('is_active', 1)->count();
    $user_count = User::where('is_active', 1)->count();
    return view('admin.dashboard', compact('module_count', 'permission_count', 'role_count', 'user_count'));
  }
  public function userIndex()
  {
    dd('here');
    return view('dashboard');
  }
}
