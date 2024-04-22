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
      $view->with('menuData', $menu);
    });

    view()->composer(['users.*'], function ($view) {
      $menu = $this->getUserMenu();
      $view->with('menuData', $menu);
    });
  }

  protected function getAdminMenu()
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson, true);

    $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson, true);

    $menu = [(object) $verticalMenuData, (object) $horizontalMenuData];
    return $menu;
  }

  protected function getUserMenu()
  {
    // Retrieve the currently authenticated user
    $user = Auth::user();

    // If user has no roles, return an empty array
    if (!$user || !$user->role) {
      return [];
    }

    // Retrieve static menu items from JSON file
    $staticMenuJson = file_get_contents(base_path('resources/menu/userVerticalMenu.json'));
    $staticMenuData = json_decode($staticMenuJson, true);

    $menu = Module::all()->filter(function ($module) use ($user) {
        return $module->parent_code === null && $user->hasPermission($module->code, 'view_access');
      })->map(function ($module) use ($user) {
        $submodulesArray = $module->submodules
          ->filter(function ($submodule) use ($user) {
            return $user->hasPermission($submodule->code, 'view_access');
          })
          ->toArray();
        if (!empty($submodulesArray)) {
          $moduleArray = $module->toArray();
          $moduleArray['submenu'] = $submodulesArray;
          return $moduleArray;
        }
        return null;
      })->filter()->toArray();
      
    $staticMenuData['menu'] = array_merge($staticMenuData['menu'], $menu);
    // Format the menu
    $menu = [(object) $staticMenuData];
    return $menu;
  }
}
