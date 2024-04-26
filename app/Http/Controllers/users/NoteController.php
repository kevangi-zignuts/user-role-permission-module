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
    // Determine user's access permissions
    $access = [
      'add'    => Auth::user()->hasPermission('note', 'add_access'),
      'view'   => Auth::user()->hasPermission('note', 'view_access'),
      'edit'   => Auth::user()->hasPermission('note', 'edit_access'),
      'delete' => Auth::user()->hasPermission('note', 'delete_access'),
    ];

    // Querying Notes with search conditions
    $notes = Note::query()->where(function ($query) use ($request){
      if($request->input('search') != null){
        $query->where('title', 'like', '%' . $request->input('search') . '%');
      }
    })->paginate(8);

    // Append search parameters to the pagination links
    $notes->appends(['search' => $request->input('search')]);

    return view('users.notes.index', ['notes' => $notes, 'search' => $request->input('search'), 'access' => $access]);
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
    $data = $request->validate([
      'title'       => 'required|string|max:64',
      'description' => 'required|string|max:255',
    ]);

    $data['user_id'] = Auth::id();
    Note::create($data);
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
      'title'       => 'required|string|max:64',
      'description' => 'required|string|max:255',
    ]);

    $note = Note::findOrFail($id);
    $note->update($request->only(['title', 'description']));

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
