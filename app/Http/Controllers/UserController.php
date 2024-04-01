<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Broadcast;

class UserController extends Controller
{
  /**
   * show a User's dashboard
   */
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $query = User::query();

    // filter the user
    if ($filter !== 'all') {
      $query->where('is_active', $request->filter)->get();
    }

    // search the user
    $search = $request->input('search');
    if (!empty($search)) {
      $query->where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%');
    }

    $users = $query->with('role')->paginate(8);
    return view('admin.users.index', ['users' => $users, 'filter' => $filter]);
  }

  /**
   * show a form for crate a new user
   */
  public function create()
  {
    $roles = Role::where('is_active', 1)->get();
    return view('admin.users.create', ['roles' => $roles]);
  }

  /**
   * store a data of new created user
   */
  public function store(Request $request)
  {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'email' => [
        'email',
        'required',
        Rule::unique('users')->where(function ($query) {
          // Consider soft deleted records as non-unique
          $query->whereNull('deleted_at');
        }),
      ],
      'contact_no' => 'numeric|nullable',
      'roles' => 'array|nullable',
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

    Mail::to($request->input('email'))->send(new InvitationEmail($token, $user->id, $user->first_name));

    $token = $user->createToken($user->email)->plainTextToken;

    $roleIds = $request->input('roles', []);

    $user->role()->sync($roleIds);

    return redirect()
      ->route('users.index')
      ->with('success', "User's data Inserted successfully!");
  }

  /**
   * toggle button to change status of user
   */
  public function updateIsActive(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $user->update(['is_active' => !$user->is_active]);

    return redirect()
      ->route('users.index')
      ->with('success', 'User status updated successfully.');
  }

  /**
   * show a Form for edit the user details
   */
  public function edit($id)
  {
    $user = User::with('role')->find($id);
    $roles = Role::where('is_active', 1)->get();
    return view('admin.users.edit', ['user' => $user, 'roles' => $roles]);
  }

  /**
   * update the user details
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'first_name' => 'required|string|max:255',
      'contact_no' => 'numeric|nullable',
      'roles' => 'array',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only(['first_name', 'last_name', 'contact_no', 'address']));
    $user->role()->sync($request->input('roles', []));

    return redirect()
      ->route('users.index')
      ->with('success', "User's data updated Successfully");
  }

  /**
   * delete the user
   */
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

  /**
   * admin reset the password of the user
   */
  public function resetPassword(Request $request, $id)
  {
    $request->validate([
      'password' => 'required|confirmed',
    ]);

    $user = User::findOrFail($id);
    $password = Hash::make($request['password']);
    $user->update(['password' => $password]);
    Mail::to($user->email)->send(new ResetPassword($user->first_name));

    return redirect()
      ->route('users.index')
      ->with('success', "User's password updated successfully");
  }

  /**
   * admin forced logout any user
   */
  public function forceLogout(Request $request)
  {
    User::findOrFail($request->input('user_id'))
      ->tokens()
      ->delete();

    return redirect()
      ->back()
      ->with('success', 'Successfully logged out the user from all devices.');
  }
}
