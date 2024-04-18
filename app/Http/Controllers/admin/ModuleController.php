<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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
    $search = $request->input('search');
    $query = Module::query();

    if ($filter != 'all' && !empty($search)) {
      $query->where('is_active', $request->filter)->where('module_name', 'like', '%' . $search . '%');
    } elseif ($filter != 'all' && empty($search)) {
      $query->where('is_active', $request->filter);
    } else {
      $query->where('module_name', 'like', '%' . $search . '%');
    }
    $modules = $query->get();
    $allModules = [];
    foreach ($modules as $module) {
      if ($module->parent_code !== null) {
        $tempModule = Module::findOrFail($module->parent_code);
        if ($tempModule->is_active == $module->is_active) {
          $allModules[] = $tempModule;
        }
      } else {
        if ($filter != 'all') {
          $filteredSubmodules = $module->submodules->filter(function ($submodule) use ($module) {
            return $module->is_active == $submodule->is_active;
          });
          $allModules = collect($allModules)->merge($filteredSubmodules);
        } else {
          $allModules = collect($allModules)->merge($module->submodules);
        }
      }
      $allModules[] = $module;
    }
    // dd($allModules);
    $modules = collect($allModules)->unique(function ($module) {
      return $module->code;
    });

    // dd($modules);

    // $modules = $query->where('parent_code', null)->paginate(10);

    return view('admin.modules.index', ['modules' => $modules, 'filter' => $filter]);
    // return view('admin.modules.index', ['modules' => $allModules, 'filter' => $filter]);
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
    // return redirect()
    //   ->route('modules.index')
    //   ->with('success', 'Module data updated successfully');
    return redirect()->route('modules.index', [
      'success' => true,
      'message' => 'Module Updated successfully!',
    ]);
  }
}
