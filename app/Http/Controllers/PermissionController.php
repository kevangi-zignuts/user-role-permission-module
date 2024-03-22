<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $query = Permission::query();

    if ($filter !== 'all') {
      $query->where('is_active', $request->filter)->get();
    }
    $permissions = $query->get();

    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('permission_name', 'like', '%' . $search . '%');
      $permissions = $query->get();
    }

    return view('permissions.index', ['permissions' => $permissions, 'filter' => $filter]);
  }

  public function create()
  {
    $modules = Module::where('parent_code', null)
      ->where('is_active', 1)
      ->get();
    return view('permissions.create', ['modules' => $modules]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'permission_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'modules' => 'required|array',
      'modules.*' => 'array|nullable',
    ]);

    $permission = Permission::create($request->only(['permission_name', 'description']));

    foreach ($request->input('modules') as $moduleCode => $modules) {
      $module = Module::where('code', $moduleCode)->first();
      if ($module) {
        $permission->module()->attach($module->code, [
          'add_access' => isset($modules['add_access']),
          'view_access' => isset($modules['view_access']),
          'edit_access' => isset($modules['edit_access']),
          'delete_access' => isset($modules['delete_access']),
        ]);
      }
    }

    return redirect()
      ->route('permissions.index')
      ->with('success', 'Permission created successfully.');
  }

  public function updateIsActive(Request $request, $id)
  {
    $permission = Permission::findOrFail($id);
    $permission->update(['is_active' => !$permission->is_active]);

    return redirect()
      ->route('permissions.index')
      ->with('success', 'Permission status updated successfully.');
  }

  public function edit($id)
  {
    $permission = Permission::with('module')->findOrFail($id);
    $modules = Module::where('parent_code', null)->get();
    return view('permissions.edit', ['permission' => $permission, 'modules' => $modules]);
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'permission_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'modules' => 'required|array',
      'modules.*' => 'array|nullable',
    ]);

    $permission = Permission::findOrFail($id);
    $permission->update($request->only(['permission_name', 'description']));

    foreach ($request->input('modules') as $moduleCode => $modules) {
      $module = Module::where('code', $moduleCode)->first();
      if ($module) {
        $permission->module()->updateExistingPivot($module->code, [
          'add_access' => isset($modules['add_access']),
          'view_access' => isset($modules['view_access']),
          'edit_access' => isset($modules['edit_access']),
          'delete_access' => isset($modules['delete_access']),
        ]);
      }
    }
    return redirect()
      ->route('permissions.index')
      ->with('success', 'Permission updated Successfully');
  }

  public function delete($id)
  {
    $permission = Permission::find($id);
    if (!$permission) {
      return redirect()
        ->route('permissions.index')
        ->with('fail', 'We can not found data');
    }
    $permission->delete();
    return redirect()
      ->route('permissions.index')
      ->with('success', 'Permission deleted successfully');
  }
}
