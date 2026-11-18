<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends BaseApiController
{
    /**
     * Register new customer account and issue Sanctum token
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        // Create initial patron profile
        CustomerProfile::create([
            'user_id' => $user->id,
            'membership_tier' => 'bronze',
            'preferred_currency' => 'BDT',
            'preferred_locale' => 'en',
        ]);

        $token = $user->createToken('sorolata_mobile_app')->plainTextToken;

        return $this->respondWithSuccess([
            'user' => new UserResource($user->load('profile')),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Customer account registered successfully.', 201);
    }

    /**
     * Authenticate customer and issue Sanctum Bearer token
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return $this->respondWithError('The provided credentials do not match our records.', 401, [
                'email' => ['Invalid credentials provided.'],
            ]);
        }

        $deviceName = $validated['device_name'] ?? 'sorolata_api_client';
        $token = $user->createToken($deviceName)->plainTextToken;

        return $this->respondWithSuccess([
            'user' => new UserResource($user->load('profile')),
            'token' => $token,
            'token_type' => 'Bearer',
        ], 'Authenticated successfully.');
    }

    /**
     * Revoke current access token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->respondWithSuccess(null, 'Logged out successfully.');
    }

    /**
     * Get authenticated user profile and patron status
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'addresses']);

        return $this->respondWithSuccess([
            'user' => new UserResource($user),
            'wishlist_count' => $user->wishlists()->count(),
            'orders_count' => $user->orders()->count(),
        ], 'Profile retrieved successfully.');
    }

    /**
     * Change user password
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return $this->respondWithError('Current password is incorrect.', 422, [
                'current_password' => ['The provided password does not match current password.'],
            ]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return $this->respondWithSuccess(null, 'Password updated successfully.');
    }
}
