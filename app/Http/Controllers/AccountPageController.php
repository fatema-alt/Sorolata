<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountPageController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', __('Please log in to view your order history and saved heirlooms.'));
        }

        $user = Auth::user();

        $recentOrders = Order::with(['items.product.media'])
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);

                if (!empty($user->phone)) {
                    $q->orWhere('customer_phone', $user->phone);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        $wishlistIds = session()->get('sorolata_wishlist', []);
        $savedArtifacts = Product::with(['artisan', 'district', 'media'])
            ->whereIn('id', $wishlistIds)
            ->take(6)
            ->get();

        if ($savedArtifacts->isEmpty()) {
            $savedArtifacts = Product::with(['artisan', 'district', 'media'])
                ->where('is_featured', true)
                ->take(3)
                ->get();
        }

        return view('account.index', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'savedArtifacts' => $savedArtifacts,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'district' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? $user->phone,
        ]);

        if (!empty($validated['address'])) {
            \App\Models\UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'is_default_shipping' => true],
                [
                    'title' => 'Home',
                    'name' => $user->name,
                    'phone' => $user->phone ?? '',
                    'email' => $user->email ?? '',
                    'address_line_1' => $validated['address'],
                    'city' => $validated['district'] ?? 'Dhaka',
                    'division' => 'Dhaka Division',
                    'country_code' => 'BD',
                ]
            );
        }

        return back()->with('success', __('Your profile details have been updated successfully.'));
    }
}
