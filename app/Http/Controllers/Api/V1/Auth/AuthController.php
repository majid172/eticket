<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user (role: user or operator).
     * Admins cannot self-register.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone'    => 'nullable|string|max:20|unique:users',
            'role'     => 'nullable',
        ]);

        if ($validator->fails()) {     
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => $request->role ?? 'passenger',
            'status'   => 'active',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => $this->userResource($user),
            'token'   => $token,
        ], 201);
    }

    /**
     * Login and issue a Sanctum token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->status === 'blocked') {
            return response()->json([
                'message' => 'Your account has been blocked. Please contact support.',
            ], 403);
        }

        // Revoke previous tokens (single session per user)
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => $this->userResource($user),
            'token'   => $token,
        ]);
    }

    /**
     * Get the authenticated user's profile.
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $this->userResource($request->user()),
        ]);
    }

    /**
     * Logout — revoke the current access token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Format user data for API response.
     */
    private function userResource(User $user): array
    {
        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'phone'  => $user->phone,
            'role'   => $user->role,
            'status' => $user->status,
        ];
    }
}
