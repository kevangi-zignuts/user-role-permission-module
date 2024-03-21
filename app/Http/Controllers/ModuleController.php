<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');

    $query = Module::query();

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

    $search = $request->input('search');
    if (!empty($search)) {
      $query
        ->whereHas('submodules', function ($q) use ($search) {
          $q->where('module_name', 'like', '%' . $search . '%');
        })
        ->orWhere('module_name', 'like', '%' . $search . '%');
      $modules = $query->get();

      // $modules = $query
      //   ->where('module_name', 'like', '%' . $search . '%')
      //   ->whereNull('parent_code')
      //   ->with('submodules')
      //   ->get();

      // dd($module);
    }
    // dd($filter);

    return view('modules', ['modules' => $modules, 'filter' => $filter]);
  }

  public function updateIsActive(Request $request, $code)
  {
    $module = Module::where('code', $code)->firstOrFail();
    // dd($module);
    $module->update(['is_active' => !$module->is_active]);

    return redirect()
      ->route('pages-page-2')
      ->with('success', 'Module status updated successfully.');
  }

  public function edit($code)
  {
    $module = Module::findOrFail($code);
    // dd($module);
    return view('modules.edit', ['module' => $module, 'filter' => $filter]);
  }

  public function update(Request $request, $code)
  {
    $module = Module::findOrFail($code);
    $module->update($request->only(['module_name', 'description']));
    return redirect()
      ->route('pages-page-2')
      ->with('success', 'Module data updated successfully');
  }
}
