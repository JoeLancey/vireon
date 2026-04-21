<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'brand_id', 'name', 'description', 'price',
        'image', 'video', 'stock', 'category', 'is_featured', 'is_archived'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('is_archived', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_archived', false);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function sizes() {
        return $this->belongsToMany(Size::class, 'product_size')->withPivot('stock');
    }

    public function getAverageRatingAttribute(): float
    {
        $average = $this->reviews()->where('is_approved', true)->avg('rating');
        return round($average ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}