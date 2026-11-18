<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Enums\StockMovementType;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InventoryService
{
    /**
     * Atomically adjust inventory quantity and log an immutable stock movement.
     */
    public function adjustStock(
        Product|ProductVariant $item,
        int $delta,
        StockMovementType $type,
        string $reason = '',
        ?Model $reference = null,
        ?User $actor = null
    ): InventoryLog {
        return DB::transaction(function () use ($item, $delta, $type, $reason, $reference, $actor) {
            $previousStock = $item->stock_quantity;
            $newStock = max(0, $previousStock + $delta);

            $item->update(['stock_quantity' => $newStock]);

            $productId = $item instanceof Product ? $item->id : $item->product_id;
            $variantId = $item instanceof ProductVariant ? $item->id : null;

            return InventoryLog::create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'type' => $type,
                'quantity_delta' => $delta,
                'previous_quantity' => $previousStock,
                'new_quantity' => $newStock,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->getKey(),
                'reason' => $reason ?: $type->name,
                'user_id' => $actor?->id,
            ]);
        });
    }

    /**
     * Reserve stock during active checkout to avoid race-condition overselling.
     */
    public function reserveStock(Product|ProductVariant $item, int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }

        if ($item instanceof Product && $item->is_unlimited_stock) {
            return true;
        }

        return DB::transaction(function () use ($item, $quantity) {
            $available = $item->stock_quantity - $item->reserved_stock;
            if ($available < $quantity) {
                return false;
            }

            $item->increment('reserved_stock', $quantity);

            $productId = $item instanceof Product ? $item->id : $item->product_id;
            $variantId = $item instanceof ProductVariant ? $item->id : null;

            InventoryLog::create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'type' => StockMovementType::RESERVATION,
                'quantity_delta' => -$quantity,
                'previous_quantity' => $item->stock_quantity,
                'new_quantity' => $item->stock_quantity,
                'reason' => "Temporary checkout reservation ({$quantity} units)",
            ]);

            return true;
        });
    }

    /**
     * Release reserved stock back to available inventory on checkout cancellation/timeout.
     */
    public function releaseStock(Product|ProductVariant $item, int $quantity): bool
    {
        if ($item instanceof Product && $item->is_unlimited_stock) {
            return true;
        }

        return DB::transaction(function () use ($item, $quantity) {
            $newReserved = max(0, $item->reserved_stock - $quantity);
            $item->update(['reserved_stock' => $newReserved]);

            return true;
        });
    }

    /**
     * Deduct inventory permanently upon order confirmation.
     */
    public function deductStock(Product|ProductVariant $item, int $quantity, Model $order): InventoryLog
    {
        return DB::transaction(function () use ($item, $quantity, $order) {
            // Release reservation and deduct real stock
            if (!($item instanceof Product && $item->is_unlimited_stock)) {
                $newReserved = max(0, $item->reserved_stock - $quantity);
                $item->update(['reserved_stock' => $newReserved]);
            }

            return $this->adjustStock(
                item: $item,
                delta: -$quantity,
                type: StockMovementType::SALE,
                reason: "Sold in Order #{$order->order_number}",
                reference: $order
            );
        });
    }
}
