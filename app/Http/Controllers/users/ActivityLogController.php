<?php

namespace App\Http\Controllers\users;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ActivityLogController extends Controller
{
  /**
   * show the listing of all activity logs
   */
  public function index(Request $request)
  {
    // $user = User::with([
    //   'role.permission.module' => function ($query) {
    //     $query
    //       ->wherePivot('add_access', true)
    //       ->orWherePivot('view_access', true)
    //       ->orWherePivot('edit_access', true)
    //       ->orWherePivot('delete_access', true);
    //   },
    // ])->find(Auth::id());

    // dd($user);

    // $userModules = $user->role->flatMap->permission
    //   ->flatMap(function ($permission) {
    //     return $permission->module ?? [];
    //   })
    //   ->unique('pivot.module_code');

    // dd($userModules);

    // $modulesWithPermission = $userModules->mapWithKeys(function ($module) {
    //   return [
    //     $module->pivot->module_code => [
    //       'module_name' => $module->module_name,
    //       'permissions' => [
    //         [
    //           'permission_name' => $module->pivot->permission_name,
    //           'add_access' => $module->pivot->add_access,
    //           'view_access' => $module->pivot->view_access,
    //           'edit_access' => $module->pivot->edit_access,
    //           'delete_access' => $module->pivot->delete_access,
    //         ],
    //       ],
    //     ],
    //   ];
    // });

    // $user = Auth::user(); // Retrieve the authenticated user
    // $result = $user->hasPermission('acc', 'delete_access');
    // dd($result);

    // code start
    $user = User::findOrFail(Auth::id());
    $modules = $user->getModuleWithPermissions();

    $currentModules = [];
    foreach ($modules as $module) {
      if ($module->module_name == 'activity_logs') {
        $currentModules[] = $module;
      }
    }
    $access = [
      'add' => 0,
      'view' => 0,
      'edit' => 0,
      'delete' => 0,
    ];
    foreach ($currentModules as $currentModule) {
      if ($currentModule->pivot->add_access == '1') {
        $access['add'] = 1;
      }
      if ($currentModule->pivot->view_access == '1') {
        $access['view'] = 1;
      }
      if ($currentModule->pivot->edit_access == '1') {
        $access['edit'] = 1;
      }
      if ($currentModule->pivot->delete_access == '1') {
        $access['delete'] = 1;
      }
    }

    // dd($access);
    $filter = $request->query('filter', 'all');
    $query = ActivityLog::query();

    // filter the user
    if ($filter !== 'all') {
      $query->where('is_active', $request->filter)->get();
    }

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('name', 'like', '%' . $search . '%');
    }
    $logs = $query->paginate(8);
    return view('users.logs.index', ['logs' => $logs, 'filter' => $filter, 'access' => $access]);
  }

  /**
   * show a form for add a new Log
   */
  public function create()
  {
    return view('users.logs.create');
  }

  /**
   * store a data of new Log
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required',
      'log' => 'required',
    ]);

    $requestData = $request->only(['name', 'type', 'log']);
    $requestData = array_filter($requestData, function ($value) {
      return !is_null($value);
    });
    $requestData['user_id'] = Auth::id();
    ActivityLog::create($requestData);
    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role Created successfully!');
    return redirect()->route('activityLogs.index', [
      'success' => true,
      'message' => 'Activity Log added successfully!',
    ]);
  }

  /**
   * toggle button to change status of log
   */
  public function updateStatus(Request $request, $id)
  {
    $log = ActivityLog::findOrFail($id);
    $log->update(['is_active' => !$log->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully status changed',
      ],
      200
    );
  }

  /**
   * show a Form for edit the log details
   */
  public function edit($id)
  {
    $log = ActivityLog::findOrFail($id);
    return view('users.logs.edit', ['log' => $log]);
  }

  /**
   * update the log's details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'type' => 'required',
      'log' => 'required',
    ]);

    $log = ActivityLog::findOrFail($id);
    $log->update($request->only(['name', 'type', 'log']));

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role updated successfully');
    return redirect()->route('activityLogs.index', [
      'success' => true,
      'message' => 'Activity Log Details updated successfully',
    ]);
  }

  /**
   * delete the people data
   */
  public function delete($id)
  {
    $log = ActivityLog::find($id);
    if (!$log) {
      // return redirect()
      //   ->route('roles.index')
      //   ->with('fail', 'We can not found data');
    }
    $log->delete();
    return Response::json(
      [
        'success' => true,
        'message' => "Successfully log's data deleted",
      ],
      200
    );
  }
}
