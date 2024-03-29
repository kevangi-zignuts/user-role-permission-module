<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
  /**
   * show a Module's dashboard
   */
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');

    $query = Module::query();

    // filter the module
    if ($filter !== 'all') {
      $query
        ->where('is_active', $request->filter)
        ->with([
          'submodules' => function ($query) use ($request) {
            $query->where('is_active', $request->filter);
          },
        ])
        ->get();
    }
    $modules = $query->where('parent_code', null)->get();

    // search the module
    $search = $request->input('search');
    if (!empty($search)) {
      $query
        ->whereHas('submodules', function ($q) use ($search) {
          $q->where('module_name', 'like', '%' . $search . '%');
        })
        ->orWhere('module_name', 'like', '%' . $search . '%');
      $modules = $query->get();
    }

    return view('modules.index', ['modules' => $modules, 'filter' => $filter]);
  }

  /**
   * toggle button to change status of module
   */
  public function updateIsActive(Request $request, $code)
  {
    $module = Module::where('code', $code)->firstOrFail();
    $module->update(['is_active' => !$module->is_active]);

    return redirect()
      ->route('pages-page-2')
      ->with('success', 'Module status updated successfully.');
  }

  /**
   * show a Form for edit the module details
   */
  public function edit($code)
  {
    $module = Module::findOrFail($code);
    return view('modules.edit', ['module' => $module, 'filter' => $filter]);
  }

  /**
   * update the update details
   */
  public function update(Request $request, $code)
  {
    $module = Module::findOrFail($code);
    $module->update($request->only(['module_name', 'description']));
    return redirect()
      ->route('pages-page-2')
      ->with('success', 'Module data updated successfully');
  }
}
