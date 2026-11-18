<?php

declare(strict_types=1);

namespace App\Enums;

enum ReturnStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ITEM_RECEIVED = 'item_received';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('Review Pending'),
            self::APPROVED => __('Return Approved (Ship to Atelier)'),
            self::REJECTED => __('Request Declined'),
            self::ITEM_RECEIVED => __('Artifact Inspected at Guild'),
            self::REFUNDED => __('Refund Executed'),
        };
    }
}
