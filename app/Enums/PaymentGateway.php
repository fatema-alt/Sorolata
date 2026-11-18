<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentGateway: string
{
    case BKASH = 'bkash';
    case NAGAD = 'nagad';
    case ROCKET = 'rocket';
    case BRAC_BANK = 'brac_bank';
    case DBBL = 'dbbl';
    case SSLCOMMERZ = 'sslcommerz';
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
    case COD = 'cash_on_delivery';
    case BANK_TRANSFER = 'bank_transfer';

    public function displayName(): string
    {
        return match ($this) {
            self::BKASH => 'bKash Mobile Banking (বিকাশ)',
            self::NAGAD => 'Nagad Mobile Banking (নগদ)',
            self::ROCKET => 'DBBL Rocket (রকেট)',
            self::BRAC_BANK => 'BRAC Bank Transfer (ব্র্যাক ব্যাংক)',
            self::DBBL => 'Dutch-Bangla Bank (ডাচ-বাংলা ব্যাংক)',
            self::SSLCOMMERZ => 'SSLCommerz Gateway (Cards & Net Banking)',
            self::STRIPE => 'International Card (Stripe Global)',
            self::PAYPAL => 'PayPal Global Express',
            self::COD => 'Cash On Delivery (ক্যাশ অন ডেলিভারি - অগ্রিম ডিসি সহ)',
            self::BANK_TRANSFER => 'Direct Bank Wire (ব্যাংক ট্রান্সফার)',
        };
    }

    public function isLocalBangladesh(): bool
    {
        return in_array($this, [self::BKASH, self::NAGAD, self::ROCKET, self::BRAC_BANK, self::DBBL, self::SSLCOMMERZ, self::COD, self::BANK_TRANSFER], true);
    }
}
