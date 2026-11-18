<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Http\Resources\Api\V1\UserAddressResource;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerApiController extends BaseApiController
{
    /**
     * Get authenticated customer profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'addresses']);

        return $this->respondWithSuccess(
            new UserResource($user),
            'Customer profile retrieved successfully.'
        );
    }

    /**
     * Update customer profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'preferred_currency' => ['nullable', 'string', 'max:3'],
            'preferred_locale' => ['nullable', 'string', 'max:5'],
        ]);

        $user->name = $validated['name'];
        if (isset($validated['phone'])) {
            $user->phone = $validated['phone'];
        }
        $user->save();

        $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);
        if (isset($validated['preferred_currency'])) {
            $profile->preferred_currency = $validated['preferred_currency'];
        }
        if (isset($validated['preferred_locale'])) {
            $profile->preferred_locale = $validated['preferred_locale'];
        }
        $profile->save();

        return $this->respondWithSuccess(
            new UserResource($user->fresh(['profile', 'addresses'])),
            'Profile updated successfully.'
        );
    }

    /**
     * Get customer saved addresses
     */
    public function addresses(Request $request): JsonResponse
    {
        $addresses = UserAddress::where('user_id', $request->user()->id)
            ->orderByDesc('is_default')
            ->get();

        return $this->respondWithSuccess(
            UserAddressResource::collection($addresses),
            'Addresses retrieved successfully.'
        );
    }

    /**
     * Create or update saved address
     */
    public function saveAddress(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            UserAddress::where('user_id', $request->user()->id)->update(['is_default' => false]);
        }

        $address = UserAddress::create(array_merge($validated, [
            'user_id' => $request->user()->id,
            'is_default' => $validated['is_default'] ?? false,
        ]));

        return $this->respondWithSuccess(
            new UserAddressResource($address),
            'Address saved successfully.',
            201
        );
    }

    /**
     * Get customer wishlist
     */
    public function wishlist(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');

        $query = Wishlist::with(['product.category', 'product.district', 'product.artisan', 'product.media']);

        if ($user) {
            $query->where('user_id', $user->id);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        } else {
            return $this->respondWithSuccess([], 'Wishlist is empty.');
        }

        $items = $query->get();
        $products = $items->map(fn($w) => $w->product)->filter();

        return $this->respondWithSuccess(
            ProductListResource::collection($products),
            'Wishlist retrieved successfully.'
        );
    }

    /**
     * Toggle product in wishlist (Add/Remove)
     */
    public function toggleWishlist(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID') ?? 'guest_' . $request->ip();

        $query = Wishlist::where('product_id', $validated['product_id']);
        if ($user) {
            $query->where('user_id', $user->id);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
            $msg = 'Product removed from wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $user?->id,
                'session_id' => $user ? null : $sessionId,
                'product_id' => $validated['product_id'],
            ]);
            $inWishlist = true;
            $msg = 'Product added to wishlist.';
        }

        return $this->respondWithSuccess([
            'in_wishlist' => $inWishlist,
            'product_id' => $validated['product_id'],
        ], $msg);
    }
}
