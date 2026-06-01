<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc|unique:users|max:255',
            'password' => 'required|string|min:8|max:64|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ], [
            'name.regex' => 'Name can only contain letters and spaces.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        $result = $this->authService->register($request->only(['name', 'email', 'password']));
        // dd(new \App\Http\Resources\UserResource($result['user']));
        //dd((new \App\Http\Resources\UserResource($result['user']))->toArray($request));
        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($this->authService->me()),
        ]);
    }

    public function refresh(): JsonResponse
    {
        $result = $this->authService->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed.',
            'data' => $result,
        ]);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->authService->changePassword(
            Auth::user(),
            $request->current_password,
            $request->new_password
        );

        if ($result !== true) {
            return response()->json([
                'success' => false,
                'message' => $result,
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully.',
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|min:2|max:255',
        ]);

        $user = $this->authService->updateProfile(Auth::user(), $request->only('name'));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
