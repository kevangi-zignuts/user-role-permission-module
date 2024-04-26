<?php

namespace App\Http\Controllers\users;

use DateTime;
use App\Models\User;
use App\Models\Meeting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MeetingController extends Controller
{
  /**
   * show a listing of all the Meetings
   */
  public function index(Request $request)
  {
    // Determine user's access permissions
    $access = [
      'add'    => Auth::user()->hasPermission('meet', 'add_access'),
      'view'   => Auth::user()->hasPermission('meet', 'view_access'),
      'edit'   => Auth::user()->hasPermission('meet', 'edit_access'),
      'delete' => Auth::user()->hasPermission('meet', 'delete_access'),
    ];

    // Querying meetings with search and filter conditions
    $meetings  = Meeting::query()->where(function ($query) use ($request){
      if($request->input('search') != null){
        $query->where('title', 'like', '%' . $request->input('search') . '%');
      }
      if($request->input('filter') && $request->input('filter') != 'all'){
        $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
      }
    })->paginate(8);

    // Append search and filter parameters to the pagination links
    $meetings->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

    return view('users.meetings.index', ['meetings' => $meetings, 'search' => $request->input('search'), 'filter' => $request->input('filter'), 'access' => $access]);
  }

  /**
   * show a form for add a new meeting
   */
  public function create()
  {
    return view('users.meetings.create');
  }

  /**
   * store a data of new Added Meeting
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'title'       => 'required|string|max:64',
      'description' => 'string|max:255',
      'date'        => 'required|date|after_or_equal:today',
      'time'        => 'required',
    ]);

    $data['user_id'] = Auth::id();
    Meeting::create($data);

    return redirect()->route('meetings.index', [
      'success' => true,
      'message' => 'Meeting added successfully!',
    ]);
  }

  /**
   * toggle button to change status of people
   */
  public function updateStatus(Request $request, $id)
  {
    $meeting = Meeting::findOrFail($id);

    $meetingDateTime = new DateTime($meeting->date . ' ' . $meeting->time);
    $currentDateTime = new DateTime();

    // Check meeting Date and Time if it is in past then can't modify the status
    if($meetingDateTime < $currentDateTime){
      return Response::json(
      [
        'error' => true,
        'message' => 'Meeting Date and Time are in past',
      ],
      200
    );
    }

    $meeting->update(['is_active' => !$meeting->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully status changed',
      ],
      200
    );
  }

  /**
   * show a Form for edit the meeting details
   */
  public function edit($id)
  {
    $meeting = Meeting::findOrFail($id);
    return view('users.meetings.edit', ['meeting' => $meeting]);
  }

  /**
   * update the meeting details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'date'  => 'required|date',
      'time'  => 'required',
    ]);

    $meeting = Meeting::findOrFail($id);
    $meeting->update($request->only(['title', 'description', 'date', 'time']));
    
    return redirect()->route('meetings.index', [
      'success' => true,
      'message' => 'Meeting Details updated successfully',
    ]);
  }

  /**
   * delete the meeting data
   */
  public function delete($id)
  {
    $meeting = Meeting::find($id);
    if (!$meeting) {
      return Response::json(
        [
          'error' => true,
          'message' => "We can not found data",
        ],
        200
      );
    }
    $meeting->delete();
    return Response::json(
      [
        'success' => true,
        'message' => "Successfully meeting's data deleted",
      ],
      200
    );
  }
}
