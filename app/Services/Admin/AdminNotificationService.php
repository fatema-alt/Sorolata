<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AdminNotificationService
{
    /**
     * Check if admin_notifications table exists
     */
    protected function isAvailable(): bool
    {
        return Schema::hasTable('admin_notifications');
    }

    /**
     * Notify administrators when a new order is placed
     */
    public function notifyOrderPlaced(Order $order): ?AdminNotification
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            $currency = $order->currency->value ?? 'BDT';
            $amount = number_format((float) $order->total_amount, 2);
            $itemsCount = $order->items()->count();

            return AdminNotification::create([
                'type' => 'order',
                'title' => "New Order Placed: #{$order->order_number}",
                'message' => "Order of ৳{$amount} ({$itemsCount} items) placed by {$order->customer_name}. Awaiting processing.",
                'action_url' => route('admin.orders.show', $order->id),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Failed to create OrderPlaced notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Notify administrators when a customer submits manual payment details (bKash/Nagad/Bank TrxID)
     */
    public function notifyPaymentSubmitted(Order $order, string $trxId, string $method): ?AdminNotification
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            $sender = $order->payment_details['sender_number'] ?? 'Not provided';

            return AdminNotification::create([
                'type' => 'payment',
                'title' => "Payment TrxID Submitted: #{$order->order_number}",
                'message' => "Customer submitted TrxID [{$trxId}] via {$method} (Sender: {$sender}). Verification required.",
                'action_url' => route('admin.orders.show', $order->id),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Failed to create PaymentSubmitted notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Notify administrators when a customer requests order cancellation
     */
    public function notifyCancellationRequested(Order $order, string $reason): ?AdminNotification
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            return AdminNotification::create([
                'type' => 'cancellation',
                'title' => "Cancellation Request: #{$order->order_number}",
                'message' => "Customer {$order->customer_name} requested cancellation. Reason: {$reason}",
                'action_url' => route('admin.orders.show', $order->id),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Failed to create CancellationRequested notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Notify administrators when product inventory falls to low stock
     */
    public function notifyLowStock(Product $product, int $remainingStock): ?AdminNotification
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            // Avoid duplicate unread low stock notification for the same product
            $existing = AdminNotification::where('type', 'stock')
                ->where('is_read', false)
                ->where('action_url', route('admin.products.edit', $product->id))
                ->exists();

            if ($existing) {
                return null;
            }

            $state = $remainingStock <= 0 ? 'OUT OF STOCK' : "Low Stock ({$remainingStock} units left)";

            return AdminNotification::create([
                'type' => 'stock',
                'title' => "{$state}: {$product->title}",
                'message' => "Atelier inventory is running critically low. Reorder or update stock ledger.",
                'action_url' => route('admin.products.edit', $product->id),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Failed to create LowStock notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Notify administrators when a customer submits a new product review
     */
    public function notifyReviewSubmitted(Review $review): ?AdminNotification
    {
        if (! $this->isAvailable()) {
            return null;
        }

        try {
            $stars = str_repeat('★', (int) $review->rating);
            $productTitle = $review->product?->title ?? 'Product';

            return AdminNotification::create([
                'type' => 'review',
                'title' => "New Review ({$stars}): {$productTitle}",
                'message' => "{$review->author_name}: \"" . \Illuminate\Support\Str::limit($review->content, 80) . "\"",
                'action_url' => route('admin.reviews.index'),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::warning("Failed to create ReviewSubmitted notification: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get count of unread notifications
     */
    public function getUnreadCount(): int
    {
        if (! $this->isAvailable()) {
            return 0;
        }

        try {
            return AdminNotification::where('is_read', false)->count();
        } catch (\Throwable) {
            return 0;
        }
    }

    /**
     * Get latest notifications for header dropdown
     *
     * @return Collection<int, AdminNotification>
     */
    public function getLatest(int $limit = 6): Collection
    {
        if (! $this->isAvailable()) {
            return new Collection();
        }

        try {
            return AdminNotification::latest()->take($limit)->get();
        } catch (\Throwable) {
            return new Collection();
        }
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(int $id): bool
    {
        if (! $this->isAvailable()) {
            return false;
        }

        try {
            $notification = AdminNotification::find($id);
            if ($notification) {
                $notification->markAsRead();
                return true;
            }
        } catch (\Throwable) {}

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): int
    {
        if (! $this->isAvailable()) {
            return 0;
        }

        try {
            return AdminNotification::where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        } catch (\Throwable) {
            return 0;
        }
    }

    /**
     * Delete a notification
     */
    public function delete(int $id): bool
    {
        if (! $this->isAvailable()) {
            return false;
        }

        try {
            return (bool) AdminNotification::where('id', $id)->delete();
        } catch (\Throwable) {
            return false;
        }
    }
}
