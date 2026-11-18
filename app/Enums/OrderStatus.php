<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAYMENT_PROCESSING = 'payment_processing';
    case CONFIRMED = 'confirmed';
    case CRAFTING_IN_PROGRESS = 'crafting_in_progress';
    case PACKED = 'packed';
    case QUALITY_CHECKED = 'quality_checked';
    case DISPATCHED = 'dispatched';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Pending Review'),
            self::PAYMENT_PROCESSING => __('Payment Processing'),
            self::CONFIRMED => __('Order Confirmed'),
            self::CRAFTING_IN_PROGRESS => __('Handcrafting In Progress'),
            self::PACKED => __('Hand-Packed with Archival Seal'),
            self::QUALITY_CHECKED => __('Curator Quality Verified'),
            self::DISPATCHED, self::SHIPPED => __('Dispatched with Courier'),
            self::DELIVERED => __('Delivered to Collector'),
            self::COMPLETED => __('Order Completed'),
            self::CANCELLED => __('Cancelled'),
            self::REFUNDED => __('Refunded'),
        };
    }

    public function labelBn(): string
    {
        return $this->label();
    }

    public function stepNumber(): int
    {
        return match ($this) {
            self::PENDING, self::PAYMENT_PROCESSING => 1,
            self::CONFIRMED => 2,
            self::CRAFTING_IN_PROGRESS, self::PACKED, self::QUALITY_CHECKED => 3,
            self::DISPATCHED, self::SHIPPED => 4,
            self::DELIVERED, self::COMPLETED => 5,
            self::CANCELLED, self::REFUNDED => 0,
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            self::PAYMENT_PROCESSING => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            self::CONFIRMED => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::CRAFTING_IN_PROGRESS => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
            self::PACKED => 'bg-teal-500/10 text-teal-400 border-teal-500/20',
            self::QUALITY_CHECKED => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20',
            self::DISPATCHED, self::SHIPPED => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
            self::DELIVERED, self::COMPLETED => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
            self::CANCELLED => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
            self::REFUNDED => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
        };
    }
}
