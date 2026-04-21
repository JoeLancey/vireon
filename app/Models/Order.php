<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'delivery_window',
        'payment_method',
        'items_count',
        'subtotal',
        'shipping_fee',
        'total',
        'placed_at',
        'estimated_arrival',
    ];

    protected $casts = [
        'items_count' => 'integer',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'estimated_arrival' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
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
}
