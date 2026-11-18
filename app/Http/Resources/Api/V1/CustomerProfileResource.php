<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CustomerProfile
 */
class CustomerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'avatar_url' => $this->avatar_url,
            'is_phone_verified' => $this->phone_verified_at !== null,
            'membership_tier' => $this->membership_tier ?? 'bronze',
            'loyalty_points' => (int) ($this->loyalty_points ?? 0),
            'preferred_currency' => $this->preferred_currency ?? 'BDT',
            'preferred_locale' => $this->preferred_locale ?? 'en',
            'orders_count' => (int) ($this->orders_count ?? 0),
            'total_spent' => (float) ($this->total_spent ?? 0.0),
        ];
    }
}
