<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // \App\Models\User::factory(10)->create();

    // \App\Models\User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);

    $user = User::create([
      'first_name' => 'System Admin',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'status' => 'A',
    ]);

    $modules = [
      [
        'code' => 'con',
        'module_name' => 'Contact',
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'com',
        'module_name' => 'Company',
        'parent_code' => 'con', // submodule
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'peo',
        'module_name' => 'People',
        'parent_code' => 'con', // submodule
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'acc',
        'module_name' => 'Account',
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'note',
        'module_name' => 'Notes',
        'parent_code' => 'acc', // submodule
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'act',
        'module_name' => 'Activity',
        'parent_code' => 'acc', // submodule
        'created_by' => 1,
        'updated_by' => 1,
      ],
      [
        'code' => 'meet',
        'module_name' => 'Meetings',
        'parent_code' => 'acc', // submodule
        'created_by' => 1,
        'updated_by' => 1,
      ],
    ];

    // Insert data into the modules table
    foreach ($modules as $module) {
      Module::create($module);
    }

    $role = Role::create([
      'role_name' => 'Admin',
    ]);

    $user->role()->attach($role);
  }
}
