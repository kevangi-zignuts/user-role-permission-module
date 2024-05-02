<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * user's dashboard
     */
    public function index()
    {
        $user = User::findOrFail(auth()->id());

        return view('users.dashboard', ['user' => $user]);
    }

    /**
     * reset password in user's dashboard
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user                 = User::findOrFail($request['userId']);
        $hashedPasswordFromDB = $user->password;
        $currentPassword      = $request['current_password'];

        if (Hash::check($currentPassword, $hashedPasswordFromDB)) {
            $user->update(['password' => Hash::make($request['password'])]);
            $user->tokens()->delete();

            return redirect()->route('auth-login-basic', [
                'success' => true,
                'message' => 'Password Reset Successfully',
            ]);
        }

        return redirect()->route('user.dashboard', [
            'error'   => true,
            'message' => 'Error In password Reset. Try Again!!',
        ]);
    }

    /**
     * show a Form for edit the user's details
     */
    public function edit()
    {
        $user = User::findOrFail(auth()->id());

        return view('users.edit', ['user' => $user]);
    }

    /**
     * update the user's details
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'contact_no' => 'numeric|nullable',
        ]);

        $user = User::findOrFail(auth()->id());
        $user->update($request->only(['first_name', 'last_name', 'contact_no', 'address']));

        return redirect()->route('user.dashboard', [
            'success' => true,
            'message' => 'User Updated successfully!',
        ]);
    }
}
