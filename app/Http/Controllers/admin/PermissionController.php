<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PermissionController extends Controller
{
    /**
     * show a Permission's dashboard
     */
    public function index(Request $request)
    {
        // Get the permission which is searched or filtered
        $permissions = Permission::query()->where(function ($query) use ($request) {
            if ($request->input('search') != null) {
                $query->where('permission_name', 'like', '%'.$request->input('search').'%');
            }
            if ($request->input('filter') && $request->input('filter') != 'all') {
                $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
            }
        })->paginate(8);
        // Append search and filter with permissions
        $permissions->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

        return view('admin.permissions.index', ['permissions' => $permissions, 'filter' => $request->input('filter'), 'search' => $request->input('search')]);
    }

    /**
     * show a form for crate a new permission
     */
    public function create()
    {
        $modules = Module::whereNull('parent_code')
            ->where('is_active', 1)
            ->get();

        return view('admin.permissions.create', ['modules' => $modules]);
    }

    /**
     * store a data of new created permission
     */
    public function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
            'description'     => 'nullable|string',
            'modules'         => 'array',
            'modules.*'       => 'array|nullable',
            'modules.*.*'     => 'boolean',
        ]);

        $permission = Permission::create($request->only(['permission_name', 'description']));

        // Attach the modules to the permission
        $permission->module()->attach($request->input('modules', []));

        session(['success' => 'Permission created successfully!!']);
        return redirect()->route('permissions.index');
    }

    /**
     * toggle button to change status of permission
     */
    public function updateStatus(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update(['is_active' => ! $permission->is_active]);

        return Response::json(
            [
                'success' => true,
                'message' => 'Status Updated Successfully',
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
        $modules = Module::whereNull('parent_code')
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

        // Sync the modules associated with the permission
        $permission->module()->sync($request->input('modules', []));

        session(['success' => 'Permission updated successfully!!']);
        return redirect()->route('permissions.index');
    }

    /**
     * delete the permission
     */
    public function delete($id)
    {
        $permission = Permission::find($id);
        if (! $permission) {
            return redirect()->route('permissions.index')->with('fail', 'We can not found data');
        }
        $permission->delete();

        return Response::json(
            [
                'success' => true,
                'message' => 'Successfully permission deleted',
            ],
            200
        );
    }
}
