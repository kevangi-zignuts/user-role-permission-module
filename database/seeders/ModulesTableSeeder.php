<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Module;

class ModulesTableSeeder extends Seeder
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
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'com',
        'module_name' => 'Company',
        'parent_code' => 'con', // submodule
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'peo',
        'module_name' => 'People',
        'parent_code' => 'con', // submodule
        'is_active' => 0,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'acc',
        'module_name' => 'Account',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'note',
        'module_name' => 'Notes',
        'parent_code' => 'acc', // submodule
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'act',
        'module_name' => 'Activity',
        'parent_code' => 'acc', // submodule
        'is_active' => 0,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'meet',
        'module_name' => 'Meetings',
        'parent_code' => 'acc', // submodule
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'dash',
        'module_name' => 'Dashboard',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'mod',
        'module_name' => 'Module Management',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'per',
        'module_name' => 'Permission Management',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'role',
        'module_name' => 'Role Management',
        'is_active' => 1,
        'created_by' => 1,
        'updated_by' => 1,
      ],
      // Add more modules as needed
    ];

    // Insert data into the modules table
    foreach ($modules as $module) {
      Module::create($module);
    }
  }
}
