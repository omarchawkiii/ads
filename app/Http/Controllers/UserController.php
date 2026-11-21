<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        return view('admin.users.index');
    }

    public function show(Request $request)
    {
        $user = User::findOrFail($request->id) ;
        return Response()->json(compact('user'));
    }

    public function get()
    {
        $users = User::all();
        return Response()->json(compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password'  => ['required', 'min:3','max:255'],
            'role'  => ['required', 'integer', 'min:0'],
            'username'  => ['required', 'string', 'min:0', 'max:255'],
            'last_name'  => ['required', 'string', 'min:0', 'max:255'],
            // Un utilisateur « Cinema Chain » (rôle 5) doit être rattaché à au moins une chaîne.
            'cinema_chain_ids'   => [$request->role == 5 ? 'required' : 'nullable', 'array', $request->role == 5 ? 'min:1' : 'min:0'],
            'cinema_chain_ids.*' => ['integer', 'exists:cinema_chains,id'],
        ]);

        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'password'        => Hash::make($request->password),
            'email_verified_at' => now(),
            'role'            => $request->role,
            'username'        => $request->username,
            'last_name'       => $request->last_name,
            'advertiser_type' => $request->role == 2 ? $request->advertiser_type : null,
        ]);

        if ($request->role == 5) {
            $user->cinemaChains()->sync($validated['cinema_chain_ids']);
        }

        return response()->json([
            'message' => 'User created successfully.',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        try
        {
            $validated = $request->validate([
                'name'            => ['required', 'string', 'max:255'],
                'last_name'       => ['required', 'string', 'min:0', 'max:255'],
                'role'            => ['required', 'integer', 'min:0'],
                'advertiser_type' => ['nullable', 'in:direct,agency'],
            ]);

            $validated['advertiser_type'] = $request->role == 2 ? $request->advertiser_type : null;

            $user->update($validated);
            return response()->json([
                'message' => 'User updated successfully.',
                'data' => $user,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try
        {
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update_password(Request $request, User $user)
    {

            $validated = $request->validate([
                'password'  => ['required', 'min:3','max:255'],
                'confirm_password' => ['required'],
            ]);
            $user->update([
                'password' => Hash::make($request->password)  ,
            ]);
            return response()->json([
                'message' => 'Password updated successfully.',
                'data' => $user,
            ]);

    }

    public function check_email(User $user, Request $request)
    {
        $exists = $user->where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function check_username(User $user, Request $request)
    {
        $exists =  $user->where('username', $request->username)->exists();
        return response()->json(['exists' => $exists]);
    }

    public function user_update_password(Request $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
             echo "The old password is incorrect.";
        }
        elseif($request->password != $request->confirm_password)
        {
            echo "The password and confirm password do not match.";
        }
        else
        {
            $user->update([
                'password' => Hash::make($request->password) ,
            ]);
            echo "success" ;
        }

    }
}
