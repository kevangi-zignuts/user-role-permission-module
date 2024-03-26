<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\InvitationEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $filter = $request->query('filter', 'all');
    $query = User::query();

    // if ($filter !== 'all') {
    //   $query->where('is_active', $request->filter)->get();
    // }
    // $roles = $query->get();

    // $search = $request->input('search');
    // if (!empty($search)) {
    //   $query->where('role_name', 'like', '%' . $search . '%');
    //   $roles = $query->get();
    // }

    // return view('users.index', ['roles' => $roles, 'filter' => $filter]);
    return view('users.index');
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
    $user->first_name = $request['first_name'];
    $user->last_name = $request['last_name'];
    $user->email = $email;
    $user->password = Hash::make($temporaryPassword);
    $user->contact_no = $request['contact_no'];
    $user->address = $request['address'];
    $user->invitation_token = $token;
    $user->status = 'I';
    $user->save();

    Mail::to($email)->send(new InvitationEmail($token, $temporaryPassword));
    dd('here');
    $role = Role::create($request->only(['role_name', 'description']));

    $permissionIds = $request->input('permissions', []);

    $role->permission()->sync($permissionIds);

    return redirect()
      ->route('roles.index')
      ->with('success', 'Roles updated successfully!');
  }
}
