<?php

namespace App\Http\Controllers\pages;

use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePage extends Controller
{
  public function index()
  {
    $module_count = Module::where('is_active', 1)->count();
    $permission_count = Permission::where('is_active', 1)->count();
    $role_count = Role::where('is_active', 1)->count();
    $user_count = User::where('is_active', 1)->count();
    // dd($module_count);
    return view('content.pages.pages-home', compact('module_count', 'permission_count', 'role_count', 'user_count'));
  }
}
