<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
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

            return response()->json(['token' => $token, 'user' => $user]);
        }

        // If remember me option is not selected
        if(Auth::attempt($credentials) && $request->hasCookie('remember_token')){
            Cookie::queue(Cookie::forget('remember_token'));
        }

        return response()->json(['token' => $token, 'user' => $user]);
    }

    // If authentication fails, redirect back with errors
    return response()->json(['message' => 'Invalid credentials'], $request->only('email', 'remember'));
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
