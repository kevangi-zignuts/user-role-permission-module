<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\People;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class PeopleController extends Controller
{
    /**
     * show a all the people listing
     */
    public function index(Request $request)
    {
        // Determine user's access permissions
        $access = [
            'add'    => Auth::user()->hasPermission('peo', 'add_access'),
            'view'   => Auth::user()->hasPermission('peo', 'view_access'),
            'edit'   => Auth::user()->hasPermission('peo', 'edit_access'),
            'delete' => Auth::user()->hasPermission('peo', 'delete_access'),
        ];

        // Querying Peoples with search and filter conditions
        $peoples = People::query()->where(function ($query) use ($request) {
            if ($request->input('search') != null) {
                $query->where('name', 'like', '%'.$request->input('search').'%');
            }
            if ($request->input('filter') && $request->input('filter') != 'all') {
                $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
            }
        })->paginate(8);

        // Append search and filter parameters to the pagination links
        $peoples->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

        return view('users.people.index', ['peoples' => $peoples, 'search' => $request->input('search'), 'filter' => $request->input('filter'), 'access' => $access]);
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
        $data = $request->validate([
            'name'        => 'required|string|max:64',
            'email'       => 'email|required',
            'designation' => 'required',
            'contact_no'  => 'numeric|nullable',
            'address'     => 'string|max:255',
        ]);

        $data['user_id'] = Auth::id();
        People::create($data);

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
        $people->update(['is_active' => ! $people->is_active]);

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
            'name'        => 'required|string|max:64',
            'email'       => 'email|required',
            'designation' => 'required',
            'contact_no'  => 'numeric|nullable',
            'address'     => 'string|max:255',
        ]);

        $people = People::findOrFail($id);
        $people->update($request->only(['name', 'email', 'designation', 'contact_no', 'address']));

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
        if (! $people) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'We can not found data',
                ],
                200
            );
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
