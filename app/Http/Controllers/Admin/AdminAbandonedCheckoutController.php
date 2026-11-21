<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCheckout;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminAbandonedCheckoutController extends Controller
{
    public function index(Request $request): View
    {
        $query = AbandonedCheckout::query();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'recovered') {
                $query->where('is_recovered', true);
            } elseif ($request->input('status') === 'abandoned') {
                $query->where('is_recovered', false);
            }
        }

        $checkouts = $query->latest('updated_at')->paginate(20)->withQueryString();

        return view('admin.orders.abandoned', [
            'checkouts' => $checkouts,
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        AbandonedCheckout::findOrFail($id)->delete();

        return back()->with('success', 'Abandoned checkout record removed.');
    }
}
