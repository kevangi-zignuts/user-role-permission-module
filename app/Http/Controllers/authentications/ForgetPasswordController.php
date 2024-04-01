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
  /**
   * show a form for enter a email to send a resetpassword link
   */
  public function showForm()
  {
    return view('content.forgetPassword.linkForEmail');
  }

  /**
   * submit a form
   */
  public function submit(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ]);

    $user = User::where('email', $request->email)->first();

    $token = Str::random(64);

    // Store the reset password token in the users table
    $user->update(['reset_password_token' => $token]);

    Mail::to($user->email)->send(new ForgetPassword($user->id, $user->first_name));

    return back()->with('message', 'We have e-mailed your password reset link!');
  }
}
