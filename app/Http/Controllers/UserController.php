<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $query = User::query();

    if ($filter !== 'all') {
      $query->where('is_active', $request->filter)->get();
    }
    $users = $query->with('role')->get();

    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%');
      $users = $query->get();
    }

    return view('users.index', ['users' => $users, 'filter' => $filter]);
    // return view('users.index');
  }

  public function create()
  {
    $roles = Role::where('is_active', 1)->get();
    return view('users.create', ['roles' => $roles]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'email' => 'email|required',
      'contact_no' => 'numeric',
      'roles' => 'array',
    ]);
    $email = $request->input('email');
    $token = md5(uniqid(rand(), true));
    $temporaryPassword = Str::random(10);

    $user = new User();
    $user->fill($request->only('first_name', 'last_name', 'email', 'contact_no', 'address'));
    $user->password = Hash::make($temporaryPassword);
    $user->invitation_token = $token;
    $user->status = 'I';
    $user->save();

    Mail::to($request->input('email'))->send(new InvitationEmail($token, $temporaryPassword));

    $roleIds = $request->input('roles', []);

    $user->role()->sync($roleIds);

    return redirect()
      ->route('users.index')
      ->with('success', "User's data Inserted successfully!");
  }

  public function updateIsActive(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $user->update(['is_active' => !$user->is_active]);

    return redirect()
      ->route('users.index')
      ->with('success', 'User status updated successfully.');
  }

  public function edit($id)
  {
    $user = User::with('role')->find($id);
    $roles = Role::where('is_active', 1)->get();
    return view('users.edit', ['user' => $user, 'roles' => $roles]);
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'contact_no' => 'numeric',
      'roles' => 'array',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only(['first_name', 'last_name', 'contact_no', 'address']));
    $user->role()->sync($request->input('roles', []));

    return redirect()
      ->route('users.index')
      ->with('success', "User's data updated Successfully");
  }

  public function delete($id)
  {
    $user = User::find($id);
    if (!$user) {
      return redirect()
        ->route('users.index')
        ->with('fail', 'We can not found data');
    }
    $user->delete();
    return redirect()
      ->route('users.index')
      ->with('success', "User's data deleted successfully");
  }

  public function resetPassword(Request $request, $id)
  {
    $request->validate([
      'password' => 'required|confirmed',
    ]);

    $user = User::findOrFail($id);
    // dd($user);
    $password = Hash::make($request['password']);
    $user->update(['password' => $password]);
    Mail::to($user->email)->send(new ResetPassword());

    return redirect()
      ->route('users.index')
      ->with('success', "User's password updated successfully");
  }

  // public function forceLogout(Request $request, $id)
  // {
  //   // Ensure $userId is a valid integer
  //   if (!is_numeric($id)) {
  //     return redirect()
  //       ->back()
  //       ->with('error', 'Invalid user ID.');
  //   }

  //   $user = User::find($id);

  //   if (!$user) {
  //     return redirect()
  //       ->back()
  //       ->with('error', 'User not found.');
  //   }

  //   // Regenerate session to force logout immediately
  //   $this->invalidateUserSession($id);

  //   return redirect()
  //     ->back()
  //     ->with('success', 'User has been forced to log out.');
  // }

  // protected function invalidateUserSession($userId)
  // {
  //   $sessionName = 'user_' . $userId . '_session';
  //   $sessionId = Session::getId();

  //   // Regenerate session ID to invalidate the current session
  //   Session::regenerate();

  //   // Delete the session data associated with the user
  //   Session::remove($sessionName);
  // }

  public function forceLogout(Request $request, $userId)
  {
    // Find the user by ID
    $user = User::findOrFail($userId);

    // Logout user from all devices
    // Auth::logoutOtherDevices($user->password);

    // If the user is logged in, logout from the current device
    if (Auth::id() == $userId) {
      Auth::logout();
    }

    return redirect()
      ->back()
      ->with('success', 'Successfully logged out the user from all devices.');
  }
}
