<?php

declare(strict_types=1);

namespace App\Services\Commerce\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Services\Commerce\Payment\Gateways\BkashGateway;
use App\Services\Commerce\Payment\Gateways\CashOnDeliveryGateway;
use App\Services\Commerce\Payment\Gateways\ManualPaymentGateway;
use App\Services\Commerce\Payment\Gateways\NagadGateway;
use App\Services\Commerce\Payment\Gateways\PayPalGateway;
use App\Services\Commerce\Payment\Gateways\RocketGateway;
use App\Services\Commerce\Payment\Gateways\SSLCommerzGateway;
use App\Services\Commerce\Payment\Gateways\StripeGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * @var array<string, class-string<PaymentGatewayContract>>
     */
    protected array $drivers = [
        'bkash' => BkashGateway::class,
        'nagad' => NagadGateway::class,
        'rocket' => RocketGateway::class,
        'brac_bank' => ManualPaymentGateway::class,
        'dbbl' => ManualPaymentGateway::class,
        'bank_transfer' => ManualPaymentGateway::class,
        'sslcommerz' => SSLCommerzGateway::class,
        'stripe' => StripeGateway::class,
        'paypal' => PayPalGateway::class,
        'cash_on_delivery' => CashOnDeliveryGateway::class,
        'cod' => CashOnDeliveryGateway::class,
    ];

    public function register(string $identifier, string $driverClass): void
    {
        $this->drivers[$identifier] = $driverClass;
    }

    public function driver(?string $identifier = null): PaymentGatewayContract
    {
        $id = $identifier ? strtolower(trim($identifier)) : 'cash_on_delivery';

        if (!isset($this->drivers[$id])) {
            throw new InvalidArgumentException("Payment gateway driver [{$id}] is not registered.");
        }

        /** @var PaymentGatewayContract $instance */
        $instance = app($this->drivers[$id]);

        return $instance;
    }

    /**
     * @return array<int, array{id: string, name: string, available: bool}>
     */
    public function getAvailableGateways(): array
    {
        $list = [];
        $uniqueDrivers = [
            'bkash' => BkashGateway::class,
            'nagad' => NagadGateway::class,
            'rocket' => RocketGateway::class,
            'sslcommerz' => SSLCommerzGateway::class,
            'stripe' => StripeGateway::class,
            'paypal' => PayPalGateway::class,
            'cash_on_delivery' => CashOnDeliveryGateway::class,
        ];

        foreach ($uniqueDrivers as $id => $class) {
            /** @var PaymentGatewayContract $gateway */
            $gateway = app($class);
            if ($gateway->isAvailable()) {
                $list[] = [
                    'id' => $gateway->getIdentifier(),
                    'name' => $gateway->getDisplayName(),
                    'available' => true,
                ];
            }
        }

        return $list;
    }
}
