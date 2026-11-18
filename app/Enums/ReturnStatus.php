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
            self::PENDING => 'Review Pending',
            self::APPROVED => 'Return Approved (Ship to Atelier)',
            self::REJECTED => 'Request Declined',
            self::ITEM_RECEIVED => 'Artifact Inspected at Guild',
            self::REFUNDED => 'Refund Executed',
        };
    }
}
