<?php

declare(strict_types=1);

namespace App\Enums;

enum AdminRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case MANAGER = 'manager';
    case CONTENT_EDITOR = 'content_editor';
    case SUPPORT = 'support';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Administrator',
            self::MANAGER => 'Storefront Manager',
            self::CONTENT_EDITOR => 'Content & Curator Editor',
            self::SUPPORT => 'Collector Concierge Support',
            self::CUSTOMER => 'Registered Collector',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'bg-[#214E34]/10 text-[#214E34] border-[#214E34]/30',
            self::MANAGER => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            self::CONTENT_EDITOR => 'bg-purple-50 text-purple-700 border-purple-200',
            self::SUPPORT => 'bg-amber-50 text-amber-700 border-amber-200',
            self::CUSTOMER => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
