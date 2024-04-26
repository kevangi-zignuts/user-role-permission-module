<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use App\Mail\forgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.forgetPassword.forgotPasswordForm', compact('pageConfigs'));
  }

  /**
   * submit a form
   */
  public function submit(Request $request)
  {
    $request->validate([
      'email' => 'required|email|exists:users',
    ]);

    $user  = User::where('email', $request->email)->first();
    $token = Str::random(64);

    // Update or insert the password reset token for the user
    DB::table('password_reset_tokens')->updateOrInsert(
      [
        'email' => $request->email,
      ],
      [
        'token' => $token,
        'created_at' => Carbon::now(),
      ]
    );

    // Send a password reset email to the user
    Mail::to($user->email)->send(new ForgetPassword($token, $user->first_name));

    return redirect()
      ->route('auth-login-basic')
      ->with('message', 'We have e-mailed your password reset link!');
  }
}
