<?php

namespace App\Http\Controllers\users;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
  /**
   * show a all the company listing
   */
  public function index(Request $request)
  {
    $access = [
      'add'    => Auth::user()->hasPermission('com', 'add_access'),
      'view'   => Auth::user()->hasPermission('com', 'view_access'),
      'edit'   => Auth::user()->hasPermission('com', 'edit_access'),
      'delete' => Auth::user()->hasPermission('com', 'delete_access'),
    ];

    $query = Company::query();

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('company_name', 'like', '%' . $search . '%');
    }
    $companies = $query->paginate(8);
    $companies->appends(['search' => $search]);
    return view('users.company.index', ['companies' => $companies, 'access' => $access]);
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
    // dd('here');
    // $validator = Validator::make($request->all(), [
    //   'company_name' => 'required|string|max:255',
    //   'owner_name'   => 'required|string|max:255',
    //   'industry'     => 'required|string|max:255',
    // ]);
    // dd($validator->fails());
    $request->validate([
      'company_name' => 'required|string|max:255',
      'owner_name'   => 'required|string|max:255',
      'industry'     => 'required|string|max:255',
    ]);

    // if ($validator->fails()) {
    //   return redirect()->back()->withErrors($validator)->withInput();
    // }

    $requestData = $request->only(['company_name', 'owner_name', 'industry']);
    $requestData = array_filter($requestData, function ($value) {
      return !is_null($value);
    });
    $requestData['user_id'] = Auth::id();
    Company::create($requestData);
    return redirect()
      ->route('company.index', [
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
    if (!$company) {
      return redirect()->route('roles.index')->with('fail', 'We can not found data');
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
