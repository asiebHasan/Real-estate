<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // register
    public function register(Request $request)
    {
        $validate = $request->validate(([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]));

        if ($validate['password'] !== $validate['password_confirmation']) {
            throw ValidationException::withMessages([
                'password' => ['The password confirmation does not match.'],
            ]);
        }


        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'role' => 'user',
            'password' => Hash::make($validate['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    // login
    public function login(Request $request)
    {
        $validate = $request->validate(([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]));

        $user = User::where('email', $validate['email'])->first();

        if (!$user || !Hash::check($validate['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    // logout
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    // get user
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }
}
