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
    $modules = Module::query()->where(function ($query) use ($request){
      if($request->input('search') != null){
        $query->where('module_name', 'like', '%' . $request->input('search') . '%');
      }
      if($request->input('filter') && $request->input('filter') != 'all'){
        $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
      }
    })->get();

    $allModules = [];
    foreach ($modules as $module) {
      if ($module->parent_code !== null) {
        $tempModule = Module::findOrFail($module->parent_code);
        if (($tempModule->is_active == $module->is_active) || $request->input('filter') == 'all') {
          $allModules[] = $tempModule;
        }
      } else {
        if ($request->input('filter') != 'all') {
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
    $modules = collect($allModules)->unique(function ($module) {
      return $module->code;
    });

    return view('admin.modules.index', ['modules' => $modules, 'search' => $request->input('search'), 'filter' => $request->input('filter')]);
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

    return redirect()->route('modules.index', [
      'success' => true,
      'message' => 'Module Updated successfully!',
    ]);
  }
}
