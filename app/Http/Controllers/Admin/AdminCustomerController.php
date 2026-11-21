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

    public function exportCsv(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
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

        $filename = 'sorolata_patrons_' . date('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Name',
                'Phone',
                'Email',
                'Total Orders',
                'Lifetime Spend (BDT)',
                'Joined Date',
            ]);

            $query->latest('created_at')->chunk(100, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->phone,
                        $user->email,
                        $user->orders_count ?? 0,
                        $user->orders_sum_total_amount ?? 0,
                        $user->created_at?->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
