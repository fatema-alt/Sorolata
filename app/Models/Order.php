<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'uuid',
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_amount',
        'paid_amount',
        'due_amount',
        'advance_delivery_charge',
        'subtotal_amount',
        'discount_amount',
        'shipping_fee',
        'tax_amount',
        'is_gift',
        'gift_wrap',
        'gift_wrap_fee',
        'gift_recipient_name',
        'gift_message',
        'gift_receipt',
        'currency',
        'status',
        'payment_status',
        'payment_gateway',
        'transaction_id',
        'payment_details',
        'shipping_rate_id',
        'tracking_number',
        'courier_name',
        'shipping_address',
        'billing_address',
        'notes',
        'locale',
    ];

    protected $casts = [
        'total_amount' => 'float',
        'paid_amount' => 'float',
        'due_amount' => 'float',
        'advance_delivery_charge' => 'float',
        'subtotal_amount' => 'float',
        'discount_amount' => 'float',
        'shipping_fee' => 'float',
        'tax_amount' => 'float',
        'gift_wrap_fee' => 'float',
        'is_gift' => 'boolean',
        'gift_wrap' => 'boolean',
        'gift_receipt' => 'boolean',
        'payment_details' => 'array',
        'currency' => Currency::class,
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'payment_gateway' => PaymentGateway::class,
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<OrderTimeline, $this>
     */
    public function timelines(): HasMany
    {
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at', 'asc');
    }

    /**
     * Alias for timelines
     *
     * @return HasMany<OrderTimeline, $this>
     */
    public function timeline(): HasMany
    {
        return $this->timelines();
    }

    /**
     * @return HasMany<ReturnRequest, $this>
     */
    public function returns(): HasMany
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function shippingRate(): BelongsTo
    {
        return $this->belongsTo(ShippingRate::class);
    }

    public function getLocaleAttribute(?string $value = null): string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->shipping_address['locale'] ?? (session('locale') ?? config('app.locale', 'en'));
    }

    public function setLocaleAttribute(?string $value): void
    {
        $this->attributes['locale'] = $value;
        $address = $this->shipping_address ?? [];
        $address['locale'] = $value;
        $this->shipping_address = $address;
    }
}
