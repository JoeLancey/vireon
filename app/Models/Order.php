<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'recipient_name',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'province',
        'postal_code',
        'country',
        'delivery_window',
        'payment_method',
        'items_count',
        'subtotal',
        'shipping_fee',
        'coupon_discount',
        'total',
        'placed_at',
        'estimated_arrival',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'coupon_id',
    ];

    protected $casts = [
        'items_count' => 'integer',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'estimated_arrival' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            default => 'Pending',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'confirmed' => '#4ADE80',
            'processing' => '#38BDF8',
            'shipped' => '#FBBF24',
            'delivered' => '#22C55E',
            'cancelled' => '#FF6B6B',
            default => '#C8FF00',
        };
    }

    public function getDeliveryWindowLabelAttribute(): string
    {
        return match ($this->delivery_window) {
            'express' => 'Express Delivery (1-2 business days)',
            default => 'Standard Delivery (3-5 business days)',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'card' => 'Credit or Debit Card',
            'cash_on_delivery' => 'Cash on Delivery',
            'gcash' => 'GCash',
            default => 'Unknown',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'processing'], true);
    }
}
