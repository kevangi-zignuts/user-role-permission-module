<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use App\Mail\forgetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
  public function showForgetPasswordForm()
  {
    return view('content.forgetPassword.linkForEmail');
  }

  public function submitForgetPasswordForm(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ]);

    $user = User::where('email', $request->email)->first();

    $token = Str::random(64);

    // Store the reset password token in the users table
    $user->update(['reset_password_token' => $token]);

    Mail::to($user->email)->send(new ForgetPassword($user->id));

    return back()->with('message', 'We have e-mailed your password reset link!');
  }

  public function resetPasswordForm($id)
  {
    return view('content.forgetPassword.passwordResetForm', compact('id'));
  }

  public function resetPassword(Request $request, $id)
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
