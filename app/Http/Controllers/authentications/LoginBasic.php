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
  public function index()
  {
    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    try {
      $validateUser = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required',
      ]);

      if ($validateUser->fails()) {
        return redirect()
          ->back()
          ->with('error', $validateUser->errors());
      }

      if (!Auth::attempt($request->only(['email', 'password']))) {
        return redirect()
          ->back()
          ->with('error', 'Email & Password does not match with our record.');
      }

      if (Auth::attempt($request->only(['email', 'password']))) {
        // dd(session()->all());
        // Authentication passed
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        return redirect()->intended('/dashboard');
      }

      $user = User::where('email', $request->email)->first();
      return redirect()
        ->route('pages-home')
        ->with('success', 'User Logged In Successfully');
    } catch (\Throwable $th) {
      return response()->json(
        [
          'status' => false,
          'message' => $th->getMessage(),
        ],
        500
      );
    }
  }

  // public function login(Request $request)
  // {
  //   dd('here');
  //   $credentials = $request->only('email', 'password');
  //   $remember = $request->has('remember');

  //   if (Auth::attempt($credentials, $remember)) {
  //     $user = Auth::user();

  //     if ($remember) {
  //       $rememberToken = Str::random(60);
  //       $user->remember_token = hash('sha256', $rememberToken);
  //       $user->save();

  //       $cookieName = 'remember_token';
  //       $cookieValue = encrypt($rememberToken);
  //       $cookieExpiration = 60 * 24 * 30; // 30 days expiration

  //       Cookie::queue($cookieName, $cookieValue, $cookieExpiration);
  //     }

  //     // Redirect to authenticated area
  //     return redirect()->intended('/dashboard');
  //   }

  //   // If authentication fails, redirect back with errors
  //   return back()
  //     ->withInput($request->only('email', 'remember'))
  //     ->withErrors(['email' => 'Invalid credentials']);
  // }

  // public function authenticate(Request $request)
  // {
  //   if (Auth::check()) {
  //     // User is already authenticated
  //   } elseif (Cookie::has('remember_token')) {
  //     $rememberToken = decrypt(Cookie::get('remember_token'));

  //     $user = User::where('remember_token', hash('sha256', $rememberToken))->first();

  //     if ($user) {
  //       Auth::login($user);
  //       // Redirect to the authenticated area
  //       return redirect('/dashboard');
  //     }
  //   }
  // }
}
