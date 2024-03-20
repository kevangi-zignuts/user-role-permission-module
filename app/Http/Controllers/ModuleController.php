<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
  public function index(Request $request)
  {
    $filter = $request->input('filter', 'all');

    $query = Module::query();

    switch ($filter) {
      case 'active':
        $query->where('is_active', 1);
        break;
      case 'inactive':
        $query->where('is_active', 0);
        break;
    }
    // dd($request->input('filter'));
    $modules = $query->get();
    // $modules = Module::all();
    // dd($modules);
    return view('modules', ['modules' => $modules]);
  }

  public function edit($code)
  {
    $module = Module::findOrFail($code);
    // dd($module);
    return view('modules.edit', ['module' => $module]);
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
