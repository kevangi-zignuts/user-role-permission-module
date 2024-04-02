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
    // User Table Seeder
    $user = User::create([
      'first_name' => 'System Admin',
      'email' => 'admin@example.com',
      'password' => Hash::make('password'),
      'status' => 'A',
    ]);

    // Module Table Data
    $modules = [
      [
        'code' => 'con',
        'module_name' => 'Contact',
        'parent_code' => null,
        'description' => null,
      ],
      [
        'code' => 'com',
        'module_name' => 'Company',
        'parent_code' => 'con', // submodule
        'description' => "submodule",
      ],
      [
        'code' => 'peo',
        'module_name' => 'People',
        'parent_code' => 'con', // submodule
        'description' => "submodule",
      ],
      [
        'code' => 'acc',
        'module_name' => 'Account',
        'parent_code' => null,
        'description' => null,
      ],
      [
        'code' => 'note',
        'module_name' => 'Notes',
        'parent_code' => 'acc', // submodule
        'description' => "submodule",
      ],
      [
        'code' => 'act',
        'module_name' => 'Activity',
        'parent_code' => 'acc', // submodule
        'description' => null,

      ],
      [
        'code' => 'meet',
        'module_name' => 'Meetings',
        'parent_code' => 'acc', // submodule
        'description' => null,

      ]
    ];

    // Insert data into the modules table
    foreach ($modules as $module) {

      // $result = Module::where('code', $module['code'])->first();

      // if($result){
      //   Module::where('code', $module['code'])->update([
      //     'module_name' => $module['module_name'],
      //     'parent_code' => $module['parent_code']
      //   ]);
      // }
      // else{
      //   Module::create($module);
      // }

      Module::updateOrCreate([
        'code' => $module['code']
      ],[
          'module_name' => $module['module_name'],
          'parent_code' => $module['parent_code']
      ]);
    }

    // $codes = array_column($modules,'code');

    Module::whereNotIn('code', array_column($modules,'code'))->delete();

    // Role Table data
    $role = Role::create([
      'role_name' => 'Admin',
    ]);

    $user->role()->attach($role);
  }
}
