<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $users = [
      [
        'first_name' => 'System Admin',
        'last_name' => null,
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'contact_no' => null,
        'address' => null,
        'is_active' => 1,
        'invitation_token' => null,
        'status' => 'A',
      ],
    ];
    foreach ($users as $user) {
      User::updateOrCreate(
        [
          'email' => $user['email'],
        ],
        [
          'first_name' => $user['first_name'],
          'last_name' => $user['last_name'],
          'password' => $user['password'],
          'contact_no' => $user['contact_no'],
          'address' => $user['address'],
          'is_active' => $user['is_active'],
          'invitation_token' => $user['invitation_token'],
          'status' => $user['status'],
        ]
      );
    }

    User::whereNotIn('email', array_column($users, 'email'))->delete();
  }
}
