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

    $roleIds = $request->input('roles', []);

    $user->role()->sync($roleIds);

    return redirect()
      ->route('roles.index')
      ->with('success', 'Roles updated successfully!');
  }

  public function updateIsActive(Request $request, $id)
  {
    $user = User::findOrFail($id);
    $user->update(['is_active' => !$user->is_active]);

    return redirect()
      ->route('users.index')
      ->with('success', 'User status updated successfully.');
  }
}
