<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email and password.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::with('roles')
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        $token = $user
            ->createToken('katokkalinis-mobile')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

                'roles' => $user->roles->map(function ($role) {
                    return [
                        'name' => $role->name,
                    ];
                })->values(),
            ],
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role' => 'required|in:resident,vendor',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please check your registration information.',
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find Role
        |--------------------------------------------------------------------------
        */

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            $user->delete();

            return response()->json([
                'success' => false,
                'message' => 'Selected role does not exist.',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Attach Role
        |--------------------------------------------------------------------------
        */

        $user->roles()->attach($role->id);

        /*
        |--------------------------------------------------------------------------
        | Create Token
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('katokkalinis-mobile')
            ->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'token' => $token,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,

                'roles' => [
                    [
                        'name' => $role->name,
                    ],
                ],
            ],
        ], 201);
    }


    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD
    |--------------------------------------------------------------------------
    */

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where(
            'email',
            $request->email
        )->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account was found with this email address.',
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Temporary Password Reset Response
        |--------------------------------------------------------------------------
        |
        | This confirms that the account exists.
        | Actual email reset will be added next.
        |
        */

        return response()->json([
            'success' => true,
            'message' => 'Password reset request received. Your email account was found.',
        ]);
    }
}
