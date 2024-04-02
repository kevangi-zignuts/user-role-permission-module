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
        'code' => 'con',
        'module_name' => 'Contact',
        'description' => null,
        'is_active' => 1,
        'parent_code' => null,
      ],
      [
        'code' => 'com',
        'module_name' => 'Company',
        'description' => 'submodule',
        'is_active' => 1,
        'parent_code' => 'con', // submodule
      ],
      [
        'code' => 'peo',
        'module_name' => 'People',
        'description' => 'submodule',
        'is_active' => 1,
        'parent_code' => 'con', // submodule
      ],
      [
        'code' => 'acc',
        'module_name' => 'Account',
        'description' => null,
        'is_active' => 1,
        'parent_code' => null,
      ],
      [
        'code' => 'note',
        'module_name' => 'Notes',
        'description' => 'submodule',
        'is_active' => 1,
        'parent_code' => 'acc', // submodule
      ],
      [
        'code' => 'act',
        'module_name' => 'Activity',
        'description' => 'submodule',
        'is_active' => 1,
        'parent_code' => 'acc', // submodule
      ],
      [
        'code' => 'meet',
        'module_name' => 'Meetings',
        'description' => 'submodule',
        'is_active' => 1,
        'parent_code' => 'acc', // submodule
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
          'is_active' => $module['is_active'],
          'parent_code' => $module['parent_code'],
        ]
      );
    }

    Module::whereNotIn('code', array_column($modules, 'code'))->delete();
  }
}
