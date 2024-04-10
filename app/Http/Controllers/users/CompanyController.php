<?php

namespace App\Http\Controllers\users;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CompanyController extends Controller
{
  /**
   * show a all the company listing
   */
  public function index(Request $request)
  {
    $user = User::findOrFail(Auth::id());
    $modules = $user->getModuleWithPermissions();
    // dd($modules);
    $currentModules = [];
    foreach ($modules as $module) {
      if ($module->module_name == 'company') {
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
      // dd($currentModules);
    }

    $query = Company::query();

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('company_name', 'like', '%' . $search . '%');
    }
    $companies = $query->paginate(8);
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
    $request->validate([
      'company_name' => 'required|string|max:255',
      'owner_name' => 'required|string|max:255',
      'industry' => 'required|string|max:255',
    ]);

    $requestData = $request->only(['company_name', 'owner_name', 'industry']);
    $requestData = array_filter($requestData, function ($value) {
      return !is_null($value);
    });
    $requestData['user_id'] = Auth::id();
    Company::create($requestData);
    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role Created successfully!');
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
      'owner_name' => 'required|string|max:255',
      'industry' => 'required|string|max:255',
    ]);

    $company = Company::findOrFail($id);
    $company->update($request->only(['company_name', 'owner_name', 'industry']));

    // return redirect()
    //   ->route('roles.index')
    //   ->with('success', 'Role updated successfully');
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
      // return redirect()
      //   ->route('roles.index')
      //   ->with('fail', 'We can not found data');
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
