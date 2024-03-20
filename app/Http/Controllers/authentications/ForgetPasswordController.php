<?php

namespace App\Http\Controllers\authentications;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\forgetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    Mail::send('content.ForgetPassword.forgetPasswordMail', ['token' => $token], function ($message) use ($request) {
      $message->to($request->email);
      $message->subject('Reset Password');
    });

    return back()->with('message', 'We have e-mailed your password reset link!');
    // $email = $request['email'];
    // Mail::to($email)->send(new ForgetPassword());
    // dd(Auth::check());
    // if (Auth::check()) {
    //   $email = Auth::user()->email;
    //   Mail::to($email)->send(new ForgetPassword($email));
    //   return redirect()
    //     ->back()
    //     ->with();
    //   return response()->json(['email' => $email]);
    // } else {
    //   return response()->json(['error' => 'User is not authenticated'], 401);
    // }
  }

  public function resetPassword(Request $request, $email)
  {
    $request->validate([
      'password' => 'required|min:8|confirmed',
    ]);
  }
}
