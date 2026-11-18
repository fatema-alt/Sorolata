<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Services\Admin\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminDiscountController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    public function index(): View
    {
        $discounts = Discount::latest()->paginate(15);

        return view('admin.discounts.index', [
            'discounts' => $discounts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'min_subtotal' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $discount = Discount::create([
            'uuid' => (string) Str::uuid(),
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'type' => DiscountType::tryFrom($validated['type']) ?? DiscountType::PERCENTAGE,
            'value' => (float) $validated['value'],
            'min_subtotal' => (float) ($validated['min_subtotal'] ?? 0),
            'max_discount_amount' => !empty($validated['max_discount_amount']) ? (float) $validated['max_discount_amount'] : null,
            'usage_limit' => !empty($validated['usage_limit']) ? (int) $validated['usage_limit'] : null,
            'is_active' => true,
        ]);

        $this->activityLog->log(
            action: 'discount.created',
            description: "Created promo code: {$discount->code}",
            subject: $discount
        );

        return back()->with('success', "Coupon [{$discount->code}] created successfully.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $discount = Discount::findOrFail($id);
        $code = $discount->code;
        $discount->delete();

        return back()->with('success', "Coupon [{$code}] removed.");
    }
}
