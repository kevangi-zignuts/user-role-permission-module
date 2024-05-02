<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ActivityLogController extends Controller
{
    /**
     * show the listing of all activity logs
     */
    public function index(Request $request)
    {
        // Determine user's access permissions
        $access = [
            'add'    => Auth::user()->hasPermission('act', 'add_access'),
            'view'   => Auth::user()->hasPermission('act', 'view_access'),
            'edit'   => Auth::user()->hasPermission('act', 'edit_access'),
            'delete' => Auth::user()->hasPermission('act', 'delete_access'),
        ];

        // Querying Activity logs with search and filter conditions
        $logs = ActivityLog::query()->where(function ($query) use ($request) {
            if ($request->input('search') != null) {
                $logs = $query->where('name', 'like', '%'.$request->input('search').'%');
            }
            if ($request->input('filter') && $request->input('filter') != 'all') {
                $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
            }
        })->paginate(8);

        // Append search and filter parameters to the pagination links
        $logs->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

        return view('users.logs.index', ['logs' => $logs, 'search' => $request->input('search'), 'filter' => $request->input('filter'), 'access' => $access]);
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'log'  => 'required',
        ]);

        $data['user_id'] = Auth::id();
        ActivityLog::create($data);

        session(['success' => 'Activity Log added successfully!']);
        return redirect()->route('activityLogs.index');
    }

    /**
     * toggle button to change status of log
     */
    public function updateStatus(Request $request, $id)
    {
        $log = ActivityLog::findOrFail($id);
        $log->update(['is_active' => ! $log->is_active]);

        return Response::json(
            [
                'success' => true,
                'message' => 'Status changed successfully',
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
            'log'  => 'required',
        ]);

        $log = ActivityLog::findOrFail($id);
        $log->update($request->only(['name', 'type', 'log']));

        session(['success' => 'Activity Log Details updated successfully!']);
        return redirect()->route('activityLogs.index');
    }

    /**
     * delete the people data
     */
    public function delete($id)
    {
        $log = ActivityLog::find($id);
        if (!$log) {
            return Response::json(
                [
                    'error' => true,
                    'message' => 'We can not found data',
                ],
                200
            );
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
