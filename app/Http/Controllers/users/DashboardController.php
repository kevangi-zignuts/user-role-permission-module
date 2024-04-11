<?php

namespace App\Http\Controllers\users;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
  public function index()
  {
    $user = User::findOrFail(auth()->id());
    return view('users.dashboard', ['user' => $user]);
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'password' => 'required|confirmed',
    ]);

    $user = User::findOrFail(auth()->id());
    $hashedPasswordFromDB = $user->password;
    $currentPassword = $request['current_password'];
    if (Hash::check($currentPassword, $hashedPasswordFromDB)) {
      $password = Hash::make($request['password']);
      $user->update(['password' => $password]);
      $user->tokens()->delete();
      return redirect()->route('auth-login-basic', [
        'success' => true,
        'message' => 'Password Reset Successfully',
      ]);
    }
    return redirect()->route('user.dashboard', [
      'error' => true,
      'message' => 'Error In password Reset. Try Again!!',
    ]);
  }

  public function edit()
  {
    $user = User::findOrFail(auth()->id());
    return view('users.edit', ['user' => $user]);
  }

  public function update(Request $request)
  {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'contact_no' => 'numeric|nullable',
    ]);

    $user = User::findOrFail(auth()->id());
    $user->update($request->only(['first_name', 'last_name', 'contact_no', 'address']));

    // return redirect()
    //   ->route('users.index')
    //   ->with('success', "User's data updated Successfully");
    return redirect()->route('user.dashboard', [
      'success' => true,
      'message' => 'User Updated successfully!',
    ]);
  }
}
