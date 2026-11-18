<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount('orders')
            ->withSum('orders', 'total_amount');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $customers = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('admin.customers.index', [
            'customers' => $customers,
        ]);
    }

    public function show(int $id): View
    {
        $customer = User::with([
            'orders.items',
            'reviews.product',
            'addresses.district',
        ])->findOrFail($id);

        $lifetimeValue = $customer->orders->sum('total_amount');

        return view('admin.customers.show', [
            'customer' => $customer,
            'lifetimeValue' => $lifetimeValue,
        ]);
    }
}
