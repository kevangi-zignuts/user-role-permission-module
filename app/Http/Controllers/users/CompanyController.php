<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CompanyController extends Controller
{
    /**
     * show a all the company listing
     */
    public function index(Request $request)
    {
        // Determine user's access permissions
        $access = [
            'add'    => Auth::user()->hasPermission('com', 'add_access'),
            'view'   => Auth::user()->hasPermission('com', 'view_access'),
            'edit'   => Auth::user()->hasPermission('com', 'edit_access'),
            'delete' => Auth::user()->hasPermission('com', 'delete_access'),
        ];

        // Querying Activity logs with search conditions
        $companies = Company::query()->where(function ($query) use ($request) {
            $query->where('company_name', 'like', '%'.$request->input('search').'%');
        })->paginate(8);

        // Append search parameter to the pagination links
        $companies->appends(['search' => $request->input('search')]);

        return view('users.company.index', ['companies' => $companies, 'access' => $access, 'search' => $request->input('search')]);
    }

    /**
     * show a form for add a new company
     */
    public function create()
    {
        return view('users.company.create');
    }

    /**
     * store a data of new added Company
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'owner_name'   => 'required|string|max:255',
            'industry'     => 'required|string|max:255',
        ]);

        $data['user_id'] = Auth::id();
        Company::create($data);

        return redirect()->route('company.index', [
            'success' => true,
            'message' => 'Company added successfully!',
        ]);
    }

    /**
     * show a Form for edit the people details
     */
    public function edit($id)
    {
        $company = Company::findOrFail($id);

        return view('users.company.edit', ['company' => $company]);
    }

    /**
     * update the people's details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'owner_name'   => 'required|string|max:255',
            'industry'     => 'required|string|max:255',
        ]);

        $company = Company::findOrFail($id);
        $company->update($request->only(['company_name', 'owner_name', 'industry']));

        return redirect()->route('company.index', [
            'success' => true,
            'message' => 'Company Details updated successfully',
        ]);
    }

    /**
     * delete the people data
     */
    public function delete($id)
    {
        $company = Company::find($id);
        if (! $company) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'We can not found data',
                ],
                200
            );
        }
        $company->delete();

        return Response::json(
            [
                'success' => true,
                'message' => "Successfully company's data deleted",
            ],
            200
        );
    }
}
