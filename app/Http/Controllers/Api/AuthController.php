<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (! Auth::attempt($credentials)) {
            return response([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $user->getPermissionsViaRoles();
        $user['permissions'] = $user['roles'][0]->permissions->pluck('name');
        unset($user['roles']);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'success' => true,
            'user_data' => $user,
            'token' => $token,
            'message' => 'Login Success',
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        $response = [
            'success' => true,
            'message' => 'Logout Success',
        ];

        return response($response, 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'confirm-password' => 'required|same:password',
        ]);

        $data = $request->except('confirm-password', 'password');

        $data['password'] = Hash::make($request->password);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Register Success',
        ];

        return response($response, 201);
    }

    public function me()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $user->getPermissionsViaRoles();
            $user['permissions'] = $user['roles'][0]->permissions->pluck('name');
            unset($user['roles']);

            return response()->json([
                'message' => 'User data',
                'data' => $user,
            ]);
        }

        return response()->json([
            'message' => 'You are not logged in',
        ], 401);
    }
}
