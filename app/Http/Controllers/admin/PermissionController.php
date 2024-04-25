<?php

namespace App\Http\Controllers\admin;

use App\Models\Module;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class PermissionController extends Controller
{
  /**
   * show a Permission's dashboard
   */
  public function index(Request $request)
  {
    $permissions = Permission::query()->where(function ($query) use ($request){
      if($request->input('search') != null){
        $query->where('permission_name', 'like', '%' . $request->input('search') . '%');
      }
      if($request->input('filter') && $request->input('filter') != 'all'){
        $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
      }
    })->paginate(8);
    $permissions->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

    return view('admin.permissions.index', ['permissions' => $permissions, 'filter' => $request->input('filter'), 'search' => $request->input('search')]);
  }

  /**
   * show a form for crate a new permission
   */
  public function create()
  {
    $modules = Module::where('parent_code', null)
                    ->where('is_active', 1)
                    ->get();
    return view('admin.permissions.create', ['modules' => $modules]);
  }

  /**
   * store a data of new created permission
   */
  public function store(Request $request)
  {
    try {
      $request->validate([
        'permission_name' => 'required|string|max:255',
        'description'     => 'nullable|string',
        'modules'         => 'array',
        'modules.*'       => 'array|nullable',
      ]);

      $permission = Permission::create($request->only(['permission_name', 'description']));
      $modules    = $request->input('modules', []);
      $permission->module()->attach($modules);

      return redirect()->route('permissions.index', [
        'success' => true,
        'message' => 'Permission created successfully!',
      ]);
    } catch (ValidationException $e) {
      return redirect() ->back()->withErrors($e->validator->errors())->withInput();
    }
  }

  /**
   * toggle button to change status of permission
   */
  public function updateStatus(Request $request, $id)
  {
    $permission = Permission::findOrFail($id);
    $permission->update(['is_active' => !$permission->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }

  /**
   * show a Form for edit the permission details
   */
  public function edit($id)
  {
    $permission = Permission::with('module')->findOrFail($id);
    $modules    = Module::whereNull('parent_code')
                        ->where('is_active', 1)
                        ->get();
    return view('admin.permissions.edit', ['permission' => $permission, 'modules' => $modules]);
  }

  /**
   * update the permission details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'permission_name' => 'required|string|max:255',
      'description'     => 'nullable|string',
      'modules'         => 'array',
      'modules.*'       => 'array|nullable',
      'modules.*.*'     => 'boolean',
    ]);
    
    $permission = Permission::findOrFail($id);
    $permission->update($request->only(['permission_name', 'description']));

    $selectedModules = $request->input('modules', []);
    
    $permission->module()->sync($selectedModules);

    return redirect()->route('permissions.index', [
      'success' => true,
      'message' => 'Permission updated successfully!',
    ]);
  }

  /**
   * delete the permission
   */
  public function delete($id)
  {
    $permission = Permission::find($id);
    if (!$permission) {
      return redirect()->route('permissions.index')->with('fail', 'We can not found data');
    }
    $permission->delete();
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }
}
