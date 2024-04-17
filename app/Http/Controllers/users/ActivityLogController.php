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
    $access = [
      'add' => Auth::user()->hasPermission('act', 'add_access'),
      'view' => Auth::user()->hasPermission('act', 'view_access'),
      'edit' => Auth::user()->hasPermission('act', 'edit_access'),
      'delete' => Auth::user()->hasPermission('act', 'delete_access'),
    ];

    $filter = $request->query('filter', 'all');
    $search = $request->input('search');
    $query = ActivityLog::query();

    if ($filter != 'all' && !empty($search)) {
      $query->where('is_active', $request->filter)->where('name', 'like', '%' . $search . '%');
    } elseif ($filter != 'all' && empty($search)) {
      $query->where('is_active', $request->filter);
    } else {
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
