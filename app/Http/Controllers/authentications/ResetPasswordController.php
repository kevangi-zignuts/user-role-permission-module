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

class ResetPasswordController extends Controller
{
  /**
   * show a form for reset password when user Invitation
   */
  public function showForm($token)
  {
    $user = User::where('invitation_token', $token)->first();
    if ($user->status === 'A') {
      return redirect()
        ->route('auth-login-basic')
        ->with('error', 'Invitation accepted already!! ');
    }
    return view('admin.users.invitationResetPasswordForm', compact('token'));
  }

  /**
   * submit reset form when user Invitation
   */
  public function submit(Request $request, $token)
  {
    // dd($token);
    $request->validate([
      'password' => 'required|confirmed',
    ]);
    $user = User::where('invitation_token', $token);
    $password = Hash::make($request['password']);
    $user->update(['password' => $password, 'status' => 'A']);
    // dd('here');
    $user = User::where('invitation_token', $token)->first();
    Mail::to($user->email)->send(new ResetPassword($user->first_name));

    Auth::login($user);

    return redirect()
      ->route('user.dashboard')
      ->with('success', "User's password updated successfully");
  }

  /**
   * show a form for reset password when user forgot the password
   */
  public function resetPasswordForm($token)
  {
    // dd('here');
    // $user = User::where('reset_password_token', $token)->first();
    // $tokenRecord = DB::table('password_reset_tokens');
    $tokenRecord = DB::table('password_reset_tokens')
      ->where('token', $token)
      ->first();
    // $tokenExpiry = Carbon::parse($user->token_expiry);
    // dd($tokenRecord, $token);
    $tokenExpiry = Carbon::parse($tokenRecord->token_expiry);
    // $currentDateTime = Carbon::now();
    // dd($tokenExpiry);
    if ($tokenExpiry->isPast()) {
      return redirect()
        ->route('auth-login-basic')
        ->with('error', 'password reset link is expire');
    }
    return view('content.forgetPassword.passwordResetForm', compact('token'));
  }

  /**
   * submit reset form when user forgot the password
   */
  public function submitForm(Request $request, $token)
  {
    // dd($token);
    $request->validate([
      'password' => 'required|confirmed',
    ]);
    // $user = User::where('reset_password_token', $token);
    $user = DB::table('password_reset_tokens')->where('token', $token);
    // $user = User::where('reset_password_token', $token)->first();
    $tokenRecord = DB::table('password_reset_tokens')
      ->where('token', $token)
      ->first();
    $user = User::where('email', $tokenRecord->email)->first();
    $password = Hash::make($request['password']);
    $user->update(['password' => $password]);

    Mail::to($tokenRecord->email)->send(new ResetPassword($user->first_name));

    return redirect()
      ->route('auth-login-basic')
      ->with('success', "User's password updated successfully");
  }
}
