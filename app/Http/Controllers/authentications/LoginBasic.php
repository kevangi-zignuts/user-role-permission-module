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
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class LoginBasic extends Controller
{
  /**
   * show a login form
   */
  public function showForm()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    $rememberToken = Cookie::get('remember_token');

    try {
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
    } catch (DecryptException $e) {
      // Handle decryption error
      Log::error('Decryption error: ' . $e->getMessage());
      // Redirect or display an error message
      return redirect()
        ->route('login')
        ->withErrors(['error' => 'Invalid remember token']);
    } catch (Throwable $e) {
      // Handle other errors
      Log::error('Error: ' . $e->getMessage());
      return redirect()
        ->route('login')
        ->withErrors(['error' => 'An unexpected error occurred']);
    }

    return view('content.authentications.auth-login-basic', [
      'pageConfigs' => $pageConfigs,
      'email' => $email ?? null,
      'password' => $password ?? null,
      'rememberToken' => $rememberToken ?? null,
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
