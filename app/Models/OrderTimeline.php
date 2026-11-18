<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasBilingualAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderTimeline extends Model
{
    use HasFactory, HasBilingualAttributes;

    protected $fillable = [
        'order_id',
        'status',
        'actor_type',
        'actor_id',
        'note',
        'note_bn',
        'customer_notified',
        'metadata',
    ];

    protected $casts = [
        'customer_notified' => 'boolean',
        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function localizedStatus(): string
    {
        $enum = \App\Enums\OrderStatus::tryFrom($this->status);
        if ($enum) {
            return $enum->label();
        }
        return __($this->status);
    }

    public function localizedNote(): string
    {
        if (app()->getLocale() === 'bn' && !empty($this->note_bn)) {
            return (string) $this->note_bn;
        }

        $note = (string) $this->note;
        if (empty($note)) {
            return '';
        }

        // Direct exact translation
        $translated = __($note);
        if ($translated !== $note) {
            return $translated;
        }

        // Dynamic patterns
        if (preg_match('/^Order placed with Cash on Delivery\. Advance delivery charge of ৳([\d\.,]+) required to confirm parcel dispatch\.$/', $note, $m)) {
            return __('Order placed with Cash on Delivery. Advance delivery charge of ৳:fee required to confirm parcel dispatch.', ['fee' => $m[1]]);
        }

        if (preg_match('/^Advance delivery fee of ৳([\d\.,]+) paid via bKash \(TrxID: ([^\)]+)\)\. Parcel queued for packaging\. Remaining ৳([\d\.,]+) due on delivery\.$/', $note, $m)) {
            return __('Advance delivery fee of ৳:paid paid via bKash (TrxID: :trx). Parcel queued for packaging. Remaining ৳:due due on delivery.', [
                'paid' => $m[1],
                'trx' => $m[2],
                'due' => $m[3],
            ]);
        }

        if (preg_match('/^Full order payment of ৳([\d\.,]+) cleared via bKash \(TrxID: ([^\)]+)\)\.$/', $note, $m)) {
            return __('Full order payment of ৳:paid cleared via bKash (TrxID: :trx).', [
                'paid' => $m[1],
                'trx' => $m[2],
            ]);
        }

        if (preg_match('/^Payment of ৳([\d\.,]+) completed via SSLCommerz \(([^\)]+)\)\. Transaction ID: (.+)\.$/', $note, $m)) {
            return __('Payment of ৳:paid completed via SSLCommerz (:method). Transaction ID: :trx.', [
                'paid' => $m[1],
                'method' => $m[2],
                'trx' => $m[3],
            ]);
        }

        if (preg_match('/^Customer submitted payment details via (.+)\. Sender: ([^,]+), TrxID: (.+)\. Awaiting verification by accounts team\.$/', $note, $m)) {
            return __('Customer submitted payment details via :method. Sender: :sender, TrxID: :trx. Awaiting verification by accounts team.', [
                'method' => __($m[1]),
                'sender' => $m[2],
                'trx' => $m[3],
            ]);
        }

        return $note;
    }
}
