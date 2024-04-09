<?php

namespace App\Providers;

use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    view()->composer(['admin.*', 'content.*'], function ($view) {
      $menu = $this->getAdminMenu();
      // dd($menu);
      $view->with('menuData', $menu);
    });

    view()->composer(['users.*'], function ($view) {
      $menu = $this->getUserMenu();
      // dd($this->getUserMenu());
      $view->with('menuData', $menu);
    });
  }

  protected function getAdminMenu()
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson, true);

    $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson, true);

    $menu = [];
    foreach ($verticalMenuData as $verticalMenuItem) {
      $menu[] = (object) ['menu' => $verticalMenuItem];
    }

    foreach ($horizontalMenuData as $horizontalMenuItem) {
      $menu[] = (object) ['menu' => $horizontalMenuItem];
    }

    return $menu;
  }

  // protected function getUserMenu()
  // {
  //   // Retrieve static menu items from JSON file
  //   $staticMenuJson = file_get_contents(base_path('resources/menu/userVerticalMenu.json'));
  //   $staticMenuData = json_decode($staticMenuJson, true);

  //   $dynamicMenuItems = Module::with('submodules')
  //     ->whereNull('parent_code')
  //     ->get()
  //     ->map(function ($module) {
  //       $submoduleNames = [];
  //       foreach ($module['submodules'] as $submodule) {
  //         $submoduleNames[] = [
  //           'code' => $submodule['code'],
  //           'name' => $submodule['module_name'],
  //           'url' => $submodule['url'],
  //           'slug' => $submodule['slug'],
  //         ];
  //       }
  //       return [
  //         'code' => $module['code'],
  //         'name' => $module['module_name'],
  //         'url' => $module['url'],
  //         'slug' => $module['slug'],
  //         'submenu' => $submoduleNames,
  //       ];
  //     })
  //     ->toArray();

  //   $staticMenuData['menu'] = array_merge($staticMenuData['menu'], $dynamicMenuItems);

  //   $menu = [];
  //   foreach ($staticMenuData as $staticMenuItem) {
  //     $menu[] = (object) ['menu' => $staticMenuItem];
  //   }
  //   // dd($menu);
  //   return $menu;
  // }

  protected function getUserMenu()
  {
    // Retrieve the currently authenticated user
    $user = Auth::user();
    // dd(!$user->role);

    // If user is not authenticated or has no roles, return an empty array
    if (!$user || !$user->role) {
      return [];
    }

    // Retrieve static menu items from JSON file
    $staticMenuJson = file_get_contents(base_path('resources/menu/userVerticalMenu.json'));
    $staticMenuData = json_decode($staticMenuJson, true);

    // Retrieve modules with permissions for the user
    $modules = $user->getModulesWithPermissions();

    // dd($modules);

    // Filter dynamic menu items based on user permissions
    $dynamicMenuItems = collect($modules)
      ->map(function ($module) use ($modules) {
        $submoduleNames = [];
        foreach ($module['submodules'] as $submodule) {
          if ($modules->contains('code', $submodule['code'])) {
            $submoduleNames[] = [
              'code' => $submodule['code'],
              'name' => $submodule['module_name'],
              'url' => $submodule['url'],
              'slug' => $submodule['slug'],
              'parent_code' => $submodule['parent_code'],
            ];
          }
        }
        return [
          'code' => $module['code'],
          'name' => $module['module_name'],
          'url' => $module['url'],
          'slug' => $module['slug'],
          'parent_code' => $module['parent_code'],
          'submenu' => $submoduleNames,
        ];
      })
      ->toArray();

    // Merge static and dynamic menu items
    $staticMenuData['menu'] = array_merge($staticMenuData['menu'], $dynamicMenuItems);

    // Format the menu
    $menu = [];
    foreach ($staticMenuData as $staticMenuItem) {
      $menu[] = (object) ['menu' => $staticMenuItem];
    }
    // dd($menu);
    return $menu;
  }
}
