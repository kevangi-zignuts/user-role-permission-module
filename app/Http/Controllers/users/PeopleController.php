<?php

namespace App\Http\Controllers\users;

use App\Models\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PeopleController extends Controller
{
  /**
   * show a all the people listing
   */
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $query = People::query();

    // filter the user
    if ($filter !== 'all') {
      $query->where('is_active', $request->filter)->get();
    }

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('name', 'like', '%' . $search . '%');
    }
    $peoples = $query->paginate(8);
    return view('users.people.index', ['peoples' => $peoples, 'filter' => $filter]);
  }

  /**
   * show a form for add a new people
   */
  public function create()
  {
    return view('users.people.create');
  }

  /**
   * store a data of new created People
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'email|required',
      'designation' => 'required',
      'contact_no' => 'numeric|nullable',
    ]);

    $requestData = $request->only(['name', 'email', 'designation', 'contact_no', 'address']);
    $requestData = array_filter($requestData, function ($value) {
      return !is_null($value);
    });
    $requestData['user_id'] = Auth::id();
    People::create($requestData);
    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role Created successfully!');
    return redirect()->route('people.index', [
      'success' => true,
      'message' => 'People added successfully!',
    ]);
  }

  /**
   * toggle button to change status of people
   */
  public function updateStatus(Request $request, $id)
  {
    $people = People::findOrFail($id);
    $people->update(['is_active' => !$people->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully status changed',
      ],
      200
    );
  }

  /**
   * show a Form for edit the people details
   */
  public function edit($id)
  {
    $people = People::findOrFail($id);
    return view('users.people.edit', ['people' => $people]);
  }

  /**
   * update the people's details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'email|required',
      'designation' => 'required',
      'contact_no' => 'numeric|nullable',
    ]);

    $people = People::findOrFail($id);
    $people->update($request->only(['name', 'email', 'designation', 'contact_no', 'address']));

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role updated successfully');
    return redirect()->route('people.index', [
      'success' => true,
      'message' => 'People Details updated successfully',
    ]);
  }

  /**
   * delete the people data
   */
  public function delete($id)
  {
    $people = People::find($id);
    if (!$people) {
      // return redirect()
      //   ->route('roles.index')
      //   ->with('fail', 'We can not found data');
    }
    $people->delete();
    return Response::json(
      [
        'success' => true,
        'message' => "Successfully people's data deleted",
      ],
      200
    );
  }
}
