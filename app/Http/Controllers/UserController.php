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
use Illuminate\Support\Facades\Response;

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
      $query->where(function ($query) use ($search) {
        $query->where('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%');
      });
    }
    $query->where('email', '!=', 'admin@example.com');
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
    $validate = $request->validate([
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
    // dd($validate);
    $email = $request->input('email');
    $token = md5(uniqid(rand(), true));
    $temporaryPassword = Str::random(10);
    $user = new User();
    $user->fill($request->only('first_name', 'last_name', 'email', 'contact_no', 'address'));
    $user->password = Hash::make($temporaryPassword);
    $user->invitation_token = $token;
    $user->status = 'I';
    $user->save();

    Mail::to($request->input('email'))->send(new InvitationEmail($token, $user->first_name));

    $token = $user->createToken($user->email)->plainTextToken;

    $roleIds = $request->input('roles', []);

    $user->role()->sync($roleIds);

    return redirect()->route('users.index', [
      'success' => true,
      'message' => 'User Created successfully!',
    ]);
  }

  /**
   * toggle button to change status of user
   */
  public function updateStatus(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $user->update(['is_active' => !$user->is_active]);
    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
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

    // return redirect()
    //   ->route('users.index')
    //   ->with('success', "User's data updated Successfully");
    return redirect()->route('users.index', [
      'success' => true,
      'message' => 'User Updated successfully!',
    ]);
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
    $user->tokens()->delete();
    $user->delete();

    // return redirect()
    //   ->route('users.index')
    //   ->with('success', "User's data deleted successfully");

    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }

  /**
   * admin reset the password of the user
   */
  public function resetPassword(Request $request)
  {
    $request->validate([
      'password' => 'required|confirmed',
    ]);
    $user = User::findOrFail($request['userId']);
    $password = Hash::make($request['password']);
    $user->update(['password' => $password]);
    Mail::to($user->email)->send(new ResetPassword($user->first_name));
    $user->tokens()->delete();

    // return redirect()
    //   ->route('users.index')
    //   ->with('success', "User's password updated successfully");
    return redirect()->route('users.index', [
      'success' => true,
      'message' => 'Password Reset Successfully',
    ]);
  }

  /**
   * admin forced logout any user
   */
  public function forceLogout($id)
  {
    User::findOrFail($id)
      ->tokens()
      ->delete();

    return Response::json(
      [
        'success' => true,
        'message' => 'Successfully user deleted',
      ],
      200
    );
  }
}
