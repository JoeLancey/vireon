<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'brand_id', 'name', 'description', 'price',
        'image', 'stock', 'category', 'is_featured'
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public function brand() {
        return $this->belongsTo(Brand::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}