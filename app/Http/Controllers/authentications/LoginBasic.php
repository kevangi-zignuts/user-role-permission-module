<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
    // dd($request->all());
    // dd($request->has('remember'));
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

      if (Auth::attempt($request->only(['email', 'password']), $request->has('remember'))) {
        // dd(session()->all());
        // Authentication passed
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
}
