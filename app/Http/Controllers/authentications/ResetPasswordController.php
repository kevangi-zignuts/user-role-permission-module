<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use App\Mail\forgetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
  /**
   * show a form for reset password
   */
  public function showForm($id)
  {
    return view('content.forgetPassword.passwordResetForm', compact('id'));
  }

  /**
   * submit form
   */
  public function submit(Request $request, $id)
  {
    $request->validate([
      'password' => 'required|confirmed',
    ]);
    $user = User::findOrFail($id);
    $password = Hash::make($request['password']);
    $user->update(['password' => $password]);
    // Mail::to($user->email)->send(new ResetPassword());

    return redirect()
      ->route('login')
      ->with('success', "User's password updated successfully");
  }
}
