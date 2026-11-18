<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin UserAddress
 */
class UserAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'district' => $this->district,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'is_default' => (bool) $this->is_default,
        ];
    }
}
