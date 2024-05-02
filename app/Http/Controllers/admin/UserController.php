<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\InvitationEmail;
use App\Mail\ResetPassword;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * show a User's dashboard
     */
    public function index(Request $request)
    {
        // Querying users with search and filter conditions
        $users = User::query()->with('role')->where(function ($query) use ($request) {
            if ($request->input('search') != null) {
                $query->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', '%'.$request->input('search').'%')->orWhere('last_name', 'like', '%'.$request->input('search').'%');
                });
            }
            if ($request->input('filter') && $request->input('filter') != 'all') {
                $query->where('is_active', $request->input('filter') == 'active' ? '1' : '0');
            }
        })->where('email', '!=', 'admin@example.com')->paginate(8);

        // Append search and filter parameters to the pagination links
        $users->appends(['search' => $request->input('search'), 'filter' => $request->input('filter')]);

        return view('admin.users.index', ['users' => $users, 'search' => $request->input('search'), 'filter' => $request->input('filter')]);
    }

    /**
     * show a form for crate a new user
     */
    public function create()
    {
        $roles = Role::where('is_active', 1)->get();

        return view('admin.users.create', ['roles' => $roles]);
    }

    /**
     * store a data of new created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => [
                                'email',
                                'required',
                                Rule::unique('users')->ignore(auth()->id())->whereNull('deleted_at'),
                            ],
            'contact_no' => 'numeric|nullable',
            'roles'      => 'array|nullable',
            'roles.*'    => 'integer',
        ]);

        $token = md5(uniqid(rand(), true));
        $user = new User();
        $user->fill(
            $request->only('first_name', 'last_name', 'email', 'contact_no', 'address') + [
                'password'         => Hash::make(Str::random(10)),
                'invitation_token' => $token,
                'status'           => 'I',
            ]);
        $user->save();

        Mail::to($request->input('email'))->send(new InvitationEmail($token, $user->first_name));

        $token = $user->createToken($user->email)->plainTextToken;

        // Attach the roles to the user
        $user->role()->attach($request->input('roles', []));

        session(['success' => 'User Created successfully!!']);
        return redirect()->route('users.index');
    }

    /**
     * toggle button to change status of user
     */
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        return Response::json(
            [
                'success' => true,
                'message' => 'Successfully user deleted',
            ],
            200
        );
    }

    /**
     * show a Form for edit the user details
     */
    public function edit($id)
    {
        $user = User::with('role')->find($id);
        $roles = Role::where('is_active', 1)->get();

        return view('admin.users.edit', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * update the user details
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'contact_no' => 'numeric|nullable',
            'roles'      => 'array',
            'roles.*'    => 'integer',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['first_name', 'last_name', 'contact_no', 'address']));

        // Sync the roles associated with the user
        $user->role()->sync($request->input('roles', []));

        session(['success' => 'User Updated successfully!!']);
        return redirect()->route('users.index');
    }

    /**
     * delete the user
     */
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return Response::json(
                [
                    'error'   => true,
                    'message' => 'We can not found data',
                ],
                200
            );
        }
        $user->tokens()->delete();
        $user->delete();

        return Response::json(
            [
                'success' => true,
                'message' => 'Successfully user deleted',
            ],
            200
        );
    }

    /**
     * admin reset the password of the user
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $user = User::findOrFail($request['userId']);

        $user->update(['password' => Hash::make($request['password'])]);

        Mail::to($user->email)->send(new ResetPassword($user->first_name));

        $user->tokens()->delete();

        session(['success' => 'Password Reset Successfully!!']);
        return redirect()->route('users.index');
    }

    /**
     * admin forced logout any user
     */
    public function forceLogout($id)
    {
        User::findOrFail($id)->tokens()->delete();

        return Response::json(
            [
                'success' => true,
                'message' => 'User ForcedLogout Successfully',
            ],
            200
        );
    }
}
