<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModuleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $modules = [
      [
        'code'        => 'con',
        'module_name' => 'contact',
        'description' => null,
        'is_active'   => 1,
        'parent_code' => null,
        'url'         => null,
        'slug'        => null,
      ],
      [
        'code'        => 'com',
        'module_name' => 'company',
        'description' => 'submodule',
        'is_active'   => 1,
        'parent_code' => 'con', // submodule
        'url'         => 'user/company/index',
        'slug'        => 'company.index',
      ],
      [
        'code'        => 'peo',
        'module_name' => 'people',
        'description' => 'submodule',
        'is_active'   => 1,
        'parent_code' => 'con', // submodule
        'url'         => 'user/people/index',
        'slug'        => 'people.index',
      ],
      [
        'code'        => 'acc',
        'module_name' => 'account',
        'description' => null,
        'is_active'   => 1,
        'parent_code' => null,
        'url'         => null,
        'slug'        => null,
      ],
      [
        'code'        => 'note',
        'module_name' => 'notes',
        'description' => 'submodule',
        'is_active'   => 1,
        'parent_code' => 'acc', // submodule
        'url'         => 'user/notes/index',
        'slug'        => 'notes.index',
      ],
      [
        'code'        => 'act',
        'module_name' => 'activity_logs',
        'description' => 'submodule',
        'is_active'   => 1,
        'parent_code' => 'acc', // submodule
        'url'         => 'user/activityLogs/index',
        'slug'        => 'activityLogs.index',
      ],
      [
        'code'        => 'meet',
        'module_name' => 'meetings',
        'description' => 'submodule',
        'is_active'   => 1,
        'parent_code' => 'acc', // submodule
        'url'         => 'user/meetings/index',
        'slug'        => 'meetings.index',
      ],
    ];

    // Insert data into the modules table
    foreach ($modules as $module) {
      Module::updateOrCreate(
        [
          'code' => $module['code'],
        ],
        [
          'module_name' => $module['module_name'],
          'description' => $module['description'],
          'is_active'   => $module['is_active'],
          'parent_code' => $module['parent_code'],
          'url'         => $module['url'],
          'slug'        => $module['slug'],
        ]
      );
    }

    Module::whereNotIn('code', array_column($modules, 'code'))->delete();
  }
}
