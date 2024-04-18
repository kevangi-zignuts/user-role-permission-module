<?php

namespace App\Http\Controllers\admin;

use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
  /**
   * show a Role's dashboard
   */
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $search = $request->input('search');
    $query = Role::query();

    // filter and search the role
    if ($filter != 'all' && !empty($search)) {
      $query->where('is_active', $request->filter)->where('role_name', 'like', '%' . $search . '%');
    } elseif ($filter != 'all' && empty($search)) {
      $query->where('is_active', $request->filter);
    } else {
      $query->where('role_name', 'like', '%' . $search . '%');
    }

    $roles = $query->paginate(8);
    $roles->appends(['search' => $search, 'filter' => $filter]);
    return view('admin.roles.index', ['roles' => $roles, 'filter' => $filter]);
  }

  /**
   * show a form for crate a new role
   */
  public function create()
  {
    $permissions = Permission::where('is_active', 1)->get();
    return view('admin.roles.create', ['permissions' => $permissions]);
  }

  /**
   * store a data of new created role
   */
  public function store(Request $request)
  {
    $request->validate([
      'role_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'permissions' => 'array',
    ]);
    $role = Role::create($request->only(['role_name', 'description']));

    $permissionIds = $request->input('permissions', []);

    $role->permission()->sync($permissionIds);

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role Created successfully!');
    return redirect()->route('roles.index', [
      'success' => true,
      'message' => 'Role Created successfully!',
    ]);
  }

  /**
   * toggle button to change status of role
   */
  public function updateStatus(Request $request, $id)
  {
    $role = Role::findOrFail($id);
    $role->update(['is_active' => !$role->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }

  /**
   * show a Form for edit the role details
   */
  public function edit($id)
  {
    $role = Role::with('permission')->find($id);
    $permissions = Permission::where('is_active', 1)->get();
    return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions]);
  }

  /**
   * update the role details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'role_name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'permissions' => 'array',
    ]);

    $role = Role::findOrFail($id);
    $role->update($request->only(['role_name', 'description']));
    $role->permission()->sync($request->input('permissions', []));

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role updated successfully');
    return redirect()->route('roles.index', [
      'success' => true,
      'message' => 'Role updated successfully',
    ]);
  }

  /**
   * delete the role
   */
  public function delete($id)
  {
    $role = Role::find($id);
    if (!$role) {
      return Response::json(
        [
          'success' => true,
          'message' => 'Successfully user deleted',
        ],
        200
      );
      // return redirect()
      //   ->route('roles.index')
      //   ->with('fail', 'We can not found data');
    }
    $role->delete();
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }
}
