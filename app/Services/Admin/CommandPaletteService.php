<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class CommandPaletteService
{
    /**
     * @return array<string, array<int, array<string, string>>>
     */
    public function search(string $query = ''): array
    {
        $q = trim($query);

        $results = [
            'actions' => $this->getNavigationActions($q),
            'products' => [],
            'orders' => [],
            'customers' => [],
        ];

        if (empty($q)) {
            return $results;
        }

        // Search Products
        $products = Product::where('title', 'like', "%{$q}%")
            ->orWhere('sku', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($products as $p) {
            $results['products'][] = [
                'title' => $p->title,
                'subtitle' => "SKU: {$p->sku} • {$p->formatted_price}",
                'url' => route('admin.products.edit', $p->id),
                'icon' => 'package',
            ];
        }

        // Search Orders
        $orders = Order::where('order_number', 'like', "%{$q}%")
            ->orWhere('customer_name', 'like', "%{$q}%")
            ->orWhere('customer_phone', 'like', "%{$q}%")
            ->take(5)
            ->get();

        foreach ($orders as $o) {
            $results['orders'][] = [
                'title' => "Order #{$o->order_number}",
                'subtitle' => "{$o->customer_name} • ৳" . number_format($o->total_amount, 0) . " • {$o->status->label()}",
                'url' => route('admin.orders.show', $o->id),
                'icon' => 'shopping-bag',
            ];
        }

        // Search Customers
        $customers = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->take(4)
            ->get();

        foreach ($customers as $c) {
            $results['customers'][] = [
                'title' => $c->name,
                'subtitle' => $c->email . " • " . ($c->role?->label() ?? 'Customer'),
                'url' => route('admin.customers.show', $c->id),
                'icon' => 'user',
            ];
        }

        return $results;
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function getNavigationActions(string $q): array
    {
        $actions = [
            [
                'title' => 'Create New Heritage Artifact',
                'subtitle' => 'Draft a new product record',
                'url' => route('admin.products.create'),
                'icon' => 'plus-circle',
            ],
            [
                'title' => 'Open Visual Page Builder',
                'subtitle' => 'Launch drag-and-drop studio engine',
                'url' => route('builder.page', 1),
                'icon' => 'layout',
            ],
            [
                'title' => 'View All Orders & Deliveries',
                'subtitle' => 'Fulfillment and courier tracking',
                'url' => route('admin.orders.index'),
                'icon' => 'shopping-bag',
            ],
            [
                'title' => 'Manage Artisan Guilds',
                'subtitle' => 'Artisan profiles, workshops and bios',
                'url' => route('admin.artisans.index'),
                'icon' => 'users',
            ],
            [
                'title' => 'Storefront & Payment Settings',
                'subtitle' => 'Gateways, shipping rates and taxes',
                'url' => route('admin.settings.index'),
                'icon' => 'settings',
            ],
            [
                'title' => 'Administrative Audit Trail',
                'subtitle' => 'Immutable activity logs',
                'url' => route('admin.logs.index'),
                'icon' => 'shield-check',
            ],
        ];

        if (empty($q)) {
            return $actions;
        }

        return array_values(array_filter($actions, function ($item) use ($q) {
            return str_contains(strtolower($item['title']), strtolower($q)) ||
                   str_contains(strtolower($item['subtitle']), strtolower($q));
        }));
    }
}
