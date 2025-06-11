<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserApiResource;

class AuthController extends Controller
{
    /**
     * Register a new user (Signup)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'address'  => 'required|string',
            'phone'    => 'required|string|unique:users,phone',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(new UserApiResource(false, 'Validation error', $validator->errors()), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(new UserApiResource(true, 'User registered successfully', [
            'user'  => $user,
            'token' => $token,
        ]));
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new UserApiResource(false, 'Validation error', $validator->errors()), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(new UserApiResource(false, 'Email or password is incorrect', null), 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(new UserApiResource(true, 'Login successful', [
            'user'  => $user,
            'token' => $token,
        ]));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(new UserApiResource(true, 'Logout successful', null));
    }
}
