<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;

class LoginBasic extends Controller
{
  /**
   * show a login form
   */
  public function showForm()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    $rememberToken = Cookie::get('remember_token');
    $user = User::where('remember_token', hash('sha256', decrypt($rememberToken)))->first();
    if ($rememberToken) {
      $decryptedToken = decrypt($rememberToken);
      // Separate email and hashed password
      list($email, $password) = explode('|', $decryptedToken);
      return view('content.authentications.auth-login-basic', [
        'pageConfigs' => $pageConfigs,
        'email' => $email,
        'password' => $password,
        'rememberToken' => $rememberToken,
      ]);
    }

    return view('content.authentications.auth-login-basic', [
      'pageConfigs' => $pageConfigs,
      'email' => $email,
      'password' => $password,
      'rememberToken' => $rememberToken,
      'user' => $user,
    ]);
  }

  /**
   * login the user
   */
  public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
      $user = Auth::user();
      $token = $user->createToken($request->input('email'))->plainTextToken;
      if ($remember) {
        $rememberToken = $request->input('email') . '|' . $request->input('password');
        $user->remember_token = hash('sha256', $rememberToken);
        $user->save();

        // Store the remember token in a cookie
        $cookieName = 'remember_token';
        $cookieValue = encrypt($rememberToken);
        $cookieExpiration = 60 * 24 * 30; // 30 days expiration

        $cookie = Cookie::make($cookieName, $cookieValue, $cookieExpiration);

        return redirect()
          ->route('pages-home')
          ->with('success', 'You are logged in successfully')
          ->withCookie($cookie);
      }

      // Redirect to authenticated area
      return redirect()
        ->route('pages-home')
        ->with('success', 'You are logged in successfully');
    }

    // If authentication fails, redirect back with errors
    return back()
      ->withInput($request->only('email', 'remember'))
      ->withErrors(['email' => 'Invalid credentials']);
  }

  /**
   * logout the user
   */
  public function logout()
  {
    if (Auth::check()) {
      // Delete the user's token
      Auth::user()
        ->tokens()
        ->delete();

      return redirect()
        ->route('login')
        ->with('success', 'You have been logged out.');
    } else {
      return redirect()
        ->route('login')
        ->with('error', 'You are already logged out.');
    }
  }
}
