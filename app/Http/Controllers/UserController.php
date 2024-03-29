<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Broadcast;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('access');
  }
  public function index(Request $request)
  {
    // dd(Auth::user()->tokens()->isEmpty());

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

    // Mail::to($request->input('email'))->send(new InvitationEmail($token, $temporaryPassword, $user->id));

    $token = $user->createToken($user->email)->plainTextToken;

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
    // Mail::to($user->email)->send(new ResetPassword());

    return redirect()
      ->route('users.index')
      ->with('success', "User's password updated successfully");
  }

  public function forceLogout(Request $request)
  {
    User::findOrFail($request->input('user_id'))
      ->tokens()
      ->delete();

    return redirect()
      ->back()
      ->with('success', 'Successfully logged out the user from all devices.');
  }
  // public function forceLogout(Request $request)
  // {
  //   try {
  //     $userId = $request->input('user_id');

  //     $user = User::findOrFail($userId);
  //     $tokens = $user->tokens()->get();
  //     // dd($tokens);

  //     // Revoke all tokens associated with the user
  //     $user->tokens()->delete();
  //     // Optionally, you can also logout the user from Laravel's authentication system
  //     // Auth::logout();

  //     return redirect()
  //       ->back()
  //       ->with('success', 'Successfully logged out the user from all devices.');
  //   } catch (\Throwable $th) {
  //     return response()->json(
  //       [
  //         'status' => false,
  //         'message' => $th->getMessage(),
  //       ],
  //       500
  //     );
  //   }
  // }
}
