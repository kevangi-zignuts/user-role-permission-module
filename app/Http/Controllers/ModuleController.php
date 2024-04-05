<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
    $modules = $query->where('parent_code', null)->paginate(10);

    // search the module
    $search = $request->input('search');
    if (!empty($search)) {
      $query
        ->whereHas('submodules', function ($q) use ($search) {
          $q->where('module_name', 'like', '%' . $search . '%');
        })
        ->orWhere('module_name', 'like', '%' . $search . '%');
      $modules = $query->paginate(10);
    }

    return view('admin.modules.index', ['modules' => $modules, 'filter' => $filter]);
  }

  /**
   * toggle button to change status of module
   */
  public function updateStatus(Request $request, $code)
  {
    $module = Module::where('code', $code)->firstOrFail();
    $module->update(['is_active' => !$module->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }

  /**
   * show a Form for edit the module details
   */
  public function edit($code)
  {
    $module = Module::findOrFail($code);
    return view('admin.modules.edit', ['module' => $module]);
  }

  /**
   * update the update details
   */
  public function update(Request $request, $code)
  {
    $request->validate([
      'module_name' => 'required|string|max:255',
    ]);
    $module = Module::findOrFail($code);
    $module->update($request->only(['module_name', 'description']));
    return redirect()
      ->route('modules.index')
      ->with('success', 'Module data updated successfully');
  }
}
