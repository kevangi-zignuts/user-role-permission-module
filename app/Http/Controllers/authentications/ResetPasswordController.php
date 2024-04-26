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
      $pageConfigs = ['myLayout' => 'blank'];

      // Check if there is a password reset token associated with the provided token
      $resetPasswordToken = DB::table('password_reset_tokens')->where('token', $token)->first();
      
      // Check if there is a user with the invitation token
      $user = User::where('invitation_token', $token)->first();

      // If neither a reset password token nor a user is found with the token, redirect with error message
      if (!($resetPasswordToken || $user)) {
          return redirect()->route('auth-login-basic')->with('error', 'Password reset already!! ');
      } elseif ($resetPasswordToken) {
          return view('content.forgetPassword.passwordResetForm', compact('token', 'pageConfigs'));
      }

      if ($user) {
          if ($user->status === 'A') {
            // If user status is 'A' (accepted), redirect with error message
              return redirect()->route('auth-login-basic')->with('error', 'Invitation accepted already!! ');
          } elseif ($user->status === 'I') {
            // If user status is 'I' (invaited), load the password reset form view  
            return view('content.forgetPassword.passwordResetForm', compact('token', 'pageConfigs'));
          }
      }

      return redirect()->route('auth-login-basic')->with('error', 'Invalid or expired token');
  }

  /**
   * submit reset password form
   */
  public function submit(Request $request, $token)
  {
    $request->validate([
        'password' => 'required|confirmed',
    ]);

    // Check if a token record exists in the database
    $tokenRecord = DB::table('password_reset_tokens')->where('token', $token)->first();

    if ($tokenRecord) {
      $user = User::where('email', $tokenRecord->email)->first();

      $password = Hash::make($request['password']);
      $user->update(['password' => $password]);

      Mail::to($tokenRecord->email)->send(new ResetPassword($user->first_name));

      // Delete the used token from the database
      DB::table('password_reset_tokens')->where('token', $token)->delete();

      return redirect()->route('auth-login-basic')->with('success', "User's password updated successfully");
    }

    // If no token record exists, check if the token matches an invitation token
    $user = User::where('invitation_token', $token)->first();

    if ($user && $user->status === 'I') {
      // If an invitation token matches and the user's status is 'I', update the password and status
      $user->update(['password' => Hash::make($request['password']), 'status' => 'A']);

      $user = User::where('invitation_token', $token)->first();
      Mail::to($user->email)->send(new ResetPassword($user->first_name));

      Auth::login($user);

      return redirect()->route('user.dashboard')->with('success', "User's password updated successfully");
    }

    // If no valid token is found, redirect to the login page with an error message
    return redirect()->route('auth-login-basic')->with('error', 'Invalid token');
  }
}
