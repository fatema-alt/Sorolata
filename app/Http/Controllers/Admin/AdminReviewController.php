<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\Admin\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog
    ) {}

    public function index(): View
    {
        $reviews = Review::with(['product', 'artisan', 'user'])
            ->latest('created_at')
            ->paginate(15);

        return view('admin.reviews.index', [
            'reviews' => $reviews,
        ]);
    }

    public function reply(Request $request, int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_reply' => $validated['admin_reply'],
            'admin_replied_at' => now(),
        ]);

        $this->activityLog->log(
            action: 'review.replied',
            description: "Curator replied to review on: {$review->product?->title}",
            subject: $review
        );

        return back()->with('success', 'Curator response published.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
