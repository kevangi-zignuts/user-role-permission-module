<?php

namespace App\Providers;

use App\Models\Module;
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

    $menu = [];
    foreach ($verticalMenuData as $verticalMenuItem) {
      $menu[] = (object) ['menu' => $verticalMenuItem];
    }

    foreach ($horizontalMenuData as $horizontalMenuItem) {
      $menu[] = (object) ['menu' => $horizontalMenuItem];
    }

    return $menu;
  }

  protected function getUserMenu()
  {
    // Retrieve static menu items from JSON file
    $staticMenuJson = file_get_contents(base_path('resources/menu/userVerticalMenu.json'));
    $staticMenuData = json_decode($staticMenuJson, true);

    $dynamicMenuItems = Module::with('submodules')
      ->whereNull('parent_code')
      ->get()
      ->map(function ($module) {
        $submoduleNames = [];
        foreach ($module['submodules'] as $submodule) {
          $submoduleNames[] = [
            'name' => $submodule['module_name'],
            'url' => $submodule['url'],
            'slug' => $submodule['slug'],
          ];
        }
        return [
          'name' => $module['module_name'],
          'url' => $module['url'],
          'slug' => $module['slug'],
          'submenu' => $submoduleNames,
        ];
      })
      ->toArray();

    $staticMenuData['menu'] = array_merge($staticMenuData['menu'], $dynamicMenuItems);

    $menu = [];
    foreach ($staticMenuData as $staticMenuItem) {
      $menu[] = (object) ['menu' => $staticMenuItem];
    }
    // dd($menu);
    return $menu;
  }
}
