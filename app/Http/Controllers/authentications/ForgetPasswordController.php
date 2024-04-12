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
    return view('content.forgetPassword.forgotPasswordForm');
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

    $now = Carbon::now();
    $tokenExpiry = $now->addSeconds(120);
    DB::table('password_reset_tokens')->updateOrInsert(
      [
        'email' => $request->email,
      ],
      [
        'token' => $token,
        'token_expiry' => $tokenExpiry,
        'created_at' => Carbon::now(),
      ]
    );

    Mail::to($user->email)->send(new ForgetPassword($token, $user->first_name));

    return redirect()
      ->route('auth-login-basic')
      ->with('message', 'We have e-mailed your password reset link!');
  }
}
