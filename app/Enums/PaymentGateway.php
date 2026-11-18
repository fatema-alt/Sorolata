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
            self::BKASH => __('bKash Mobile Banking'),
            self::NAGAD => __('Nagad Mobile Banking'),
            self::ROCKET => __('DBBL Rocket'),
            self::BRAC_BANK => __('BRAC Bank Transfer'),
            self::DBBL => __('Dutch-Bangla Bank'),
            self::SSLCOMMERZ => __('SSLCommerz Gateway (Cards & Net Banking)'),
            self::STRIPE => __('International Card (Stripe Global)'),
            self::PAYPAL => __('PayPal Global Express'),
            self::COD => __('Cash On Delivery (COD)'),
            self::BANK_TRANSFER => __('Direct Bank Wire'),
        };
    }

    public function label(): string
    {
        return $this->displayName();
    }

    public function isLocalBangladesh(): bool
    {
        return in_array($this, [self::BKASH, self::NAGAD, self::ROCKET, self::BRAC_BANK, self::DBBL, self::SSLCOMMERZ, self::COD, self::BANK_TRANSFER], true);
    }
}
