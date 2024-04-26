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
    // Querying roles with search and filter conditions
    $roles  = Role::query()->where(function ($query) use ($request){
      if($request->input('search') != null){
        $query->where('role_name', 'like', '%' . $request->input('search') . '%');
      }
      if($request->input('filter') && $request->input('filter') != 'all'){
        $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
      }
    })->paginate(8);

    // Append search and filter parameters to the pagination links
    $roles->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);
    return view('admin.roles.index', ['roles' => $roles, 'filter' => $request->input('filter'), 'search' => $request->input('search')]);
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
      'role_name'     => 'required|string|max:255',
      'description'   => 'nullable|string',
      'permissions'   => 'array',
      'permissions.*' => 'integer',
    ]);
    $role = Role::create($request->only(['role_name', 'description']));

    // Attach the permissions to the role
    $role->permission()->attach($request->input('permissions', []));

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
    $role        = Role::with('permission')->find($id);
    $permissions = Permission::where('is_active', 1)->get();
    return view('admin.roles.edit', ['role' => $role, 'permissions' => $permissions]);
  }

  /**
   * update the role details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'role_name'     => 'required|string|max:255',
      'description'   => 'nullable|string',
      'permissions'   => 'array',
      'permissions.*' => 'integer',
    ]);

    $role = Role::findOrFail($id);
    $role->update($request->only(['role_name', 'description']));

    // Sync the permissions associated with the role
    $role->permission()->sync($request->input('permissions', []));

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
          'error' => true,
          'message' => 'Some error in deleting User',
        ],
        200
      );
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
