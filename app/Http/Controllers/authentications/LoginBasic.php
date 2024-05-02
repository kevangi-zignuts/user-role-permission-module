<?php

namespace App\Http\Controllers\authentications;

use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginBasic extends Controller
{
  /**
   * show a login form
   */
  public function loginForm()
  {
    $pageConfigs   = ['myLayout' => 'blank'];
    $rememberToken = Cookie::get('remember_token');

    try {
        if ($rememberToken) {
            $decryptedToken = decrypt($rememberToken);

            // Separate email and hashed password
            list($email, $password) = explode('|', $decryptedToken);

            return view('content.authentications.auth-login-basic', [
              'pageConfigs' => $pageConfigs, 'email' => $email, 'password' => $password, 'rememberToken' => $rememberToken ]);
        }
    } catch (DecryptException $e) {
        // Handle decryption error
        Log::error('Decryption error: ' . $e->getMessage());

        session(['error' => 'Invalid remember token!']);
        return redirect()->route('login');
    }

    return view('content.authentications.auth-login-basic', [
      'pageConfigs'   => $pageConfigs,
      'email'         => $email ?? null,
      'password'      => $password ?? null,
      'rememberToken' => $rememberToken ?? null,
    ]);
  }

  /**
   * login the user
   */
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');
    $remember    = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
      $user = Auth::user();
      $user->is_active = 1;
      $token = $user->createToken($request->input('email'))->plainTextToken;

      if ($remember) {
          $rememberToken = $request->input('email') . '|' . $request->input('password');
          $user->remember_token = hash('sha256', $rememberToken);
          $user->save();

          // Store the remember token in a cookie
          $cookie = Cookie::make('remember_token', encrypt($rememberToken), 60 * 24 * 30);

          if ($user->email === 'admin@example.com') {
            session(['success' => 'You are logged in successfully!']);
            return redirect()->route('admin.dashboard')->withCookie($cookie);
          }

          session(['success' => 'You are logged in successfully!']);
          return redirect()->route('user.dashboard')->withCookie($cookie);
      }

      // If remember me option is not selected
      if(Auth::attempt($credentials) && $request->hasCookie('remember_token')){
        Cookie::queue(Cookie::forget('remember_token'));
      }

      // Redirect the user based on their role
      if ($user->email === 'admin@example.com') {
        session(['success' => 'You are logged in successfully!']);
        return redirect()->route('admin.dashboard');
      }
      return redirect()->route('user.dashboard')->with('success', 'You are logged in successfully');
    }

    // If authentication fails, redirect back with errors
    session(['error' => 'Invalid credentials']);
    return back()->withInput($request->only('email', 'remember'));
  }

  /**
   * logout the user
   */
  public function logout()
  {
    if (Auth::check()) {
      $user = Auth::user();
      $user->tokens()->delete();
      Auth::logout();

      return Response::json(
        [
          'success' => true,
          'message' => 'You have been logged out.',
        ],
        200
      );
    } else {
      session(['error' => 'You are already logged out!!']);
    }
  }
}
