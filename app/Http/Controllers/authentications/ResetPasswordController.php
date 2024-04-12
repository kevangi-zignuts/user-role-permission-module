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
   * show a form for reset password
   */
  public function showForm($token)
  {
    $tokenRecord = DB::table('password_reset_tokens')
      ->where('token', $token)
      ->first();

    $tokenExpiry = $tokenRecord ? Carbon::parse($tokenRecord->token_expiry) : null;

    if ($tokenRecord && !$tokenExpiry->isPast()) {
      // Token is a password reset token
      return view('content.forgetPassword.passwordResetForm', compact('token'));
    }

    $user = User::where('invitation_token', $token)->first();
    if ($user) {
      if ($user->status === 'A') {
        // User already accepted the invitation
        return redirect()
          ->route('auth-login-basic')
          ->with('error', 'Invitation accepted already!! ');
      } elseif ($user->status === 'I') {
        return view('admin.users.invitationResetPasswordForm', compact('token'));
      }
    }

    // Default case: Token is invalid or expired, redirect to login with error message
    return redirect()
      ->route('auth-login-basic')
      ->with('error', 'Invalid or expired token');
  }

  /**
   * submit reset password form
   */
  public function submit(Request $request, $token)
  {
    // dd($token);
    $request->validate([
      'password' => 'required|confirmed',
    ]);

    $tokenRecord = DB::table('password_reset_tokens')
      ->where('token', $token)
      ->first();

    if ($tokenRecord) {
      $user = User::where('email', $tokenRecord->email)->first();

      $password = Hash::make($request['password']);
      $user->update(['password' => $password]);

      Mail::to($tokenRecord->email)->send(new ResetPassword($user->first_name));

      return redirect()
        ->route('auth-login-basic')
        ->with('success', "User's password updated successfully");
    }

    $user = User::where('invitation_token', $token)->first();

    if ($user && $user->status === 'I') {
      $password = Hash::make($request['password']);
      $user->update(['password' => $password, 'status' => 'A']);

      $user = User::where('invitation_token', $token)->first();
      Mail::to($user->email)->send(new ResetPassword($user->first_name));

      Auth::login($user);

      return redirect()
        ->route('user.dashboard')
        ->with('success', "User's password updated successfully");
    }

    return redirect()
      ->route('auth-login-basic')
      ->with('error', 'Invalid token');
  }
}
