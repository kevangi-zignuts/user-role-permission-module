<?php

namespace App\Http\Controllers\users;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NoteController extends Controller
{
  /**
   * show a all the note listing
   */
  public function index(Request $request)
  {
    $access = [
      'add' => Auth::user()->hasPermission('note', 'add_access'),
      'view' => Auth::user()->hasPermission('note', 'view_access'),
      'edit' => Auth::user()->hasPermission('note', 'edit_access'),
      'delete' => Auth::user()->hasPermission('note', 'delete_access'),
    ];

    $filter = $request->query('filter', 'all');
    $query = Note::query();

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('title', 'like', '%' . $search . '%');
    }
    $notes = $query->paginate(8);
    return view('users.notes.index', ['notes' => $notes, 'access' => $access]);
  }

  /**
   * show a form for add a new people
   */
  public function create()
  {
    return view('users.notes.create');
  }

  /**
   * store a data of new created People
   */
  public function store(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required',
    ]);

    $requestData = $request->only(['title', 'description']);
    $requestData = array_filter($requestData, function ($value) {
      return !is_null($value);
    });
    $requestData['user_id'] = Auth::id();
    Note::create($requestData);
    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role Created successfully!');
    return redirect()->route('notes.index', [
      'success' => true,
      'message' => 'Note added successfully!',
    ]);
  }

  /**
   * show a Form for edit the note
   */
  public function edit($id)
  {
    $note = Note::findOrFail($id);
    return view('users.notes.edit', ['note' => $note]);
  }

  /**
   * update the note's details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'required',
    ]);

    $note = Note::findOrFail($id);
    $note->update($request->only(['title', 'description']));

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role updated successfully');
    return redirect()->route('notes.index', [
      'success' => true,
      'message' => 'Note updated successfully',
    ]);
  }

  /**
   * delete the Note
   */
  public function delete($id)
  {
    $note = Note::find($id);
    if (!$note) {
      // return redirect()
      //   ->route('roles.index')
      //   ->with('fail', 'We can not found data');
    }
    $note->delete();
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully note deleted',
      ],
      200
    );
  }
}
