<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class RegisterBasic extends Controller
{
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs]);
  }

  // public function store(Request $request): RedirectResponse
  // {
  //   $request->validate([
  //     'first_name' => ['required', 'string', 'max:255'],
  //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
  //     'password' => ['required'],
  //   ]);

  //   $user = User::create([
  //     'first_name' => $request->first_name,
  //     'email' => $request->email,
  //     'password' => Hash::make($request->password),
  //     'status' => 'I',
  //   ]);
  //   $token = $user->createToken('API Token')->plainTextToken;
  //   PersonalAccessToken::create([
  //     // 'tokenable_type' => 'App\Models\User', // Provide the class name of the tokenable model
  //     'tokenable_id' => $user->id,
  //     'name' => 'API Token',
  //     'token' => hash('sha256', $token),
  //   ]);
  //   // event(new Registered($user));

  //   Auth::login($user);
  //   dd('here');
  //   return redirect()->route();
  // }
}
