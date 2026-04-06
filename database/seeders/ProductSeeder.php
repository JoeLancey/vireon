<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;

class ProductSeeder extends Seeder {
    public function run(): void {
        $nike    = Brand::where('name', 'Nike')->first();
        $adidas  = Brand::where('name', 'Adidas')->first();
        $nb      = Brand::where('name', 'New Balance')->first();

        $products = [
            ['brand_id' => $nike->id,   'name' => 'Air Max 270',          'price' => 8999,  'category' => 'footwear',    'stock' => 25, 'is_featured' => true],
            ['brand_id' => $nike->id,   'name' => 'Dri-FIT Running Tee',  'price' => 1999,  'category' => 'apparel',     'stock' => 50, 'is_featured' => false],
            ['brand_id' => $nike->id,   'name' => 'Pro Training Shorts',  'price' => 2499,  'category' => 'apparel',     'stock' => 40, 'is_featured' => false],
            ['brand_id' => $adidas->id, 'name' => 'Ultraboost 22',        'price' => 9999,  'category' => 'footwear',    'stock' => 20, 'is_featured' => true],
            ['brand_id' => $adidas->id, 'name' => 'Tiro 23 Training Pants','price' => 3499, 'category' => 'apparel',     'stock' => 35, 'is_featured' => false],
            ['brand_id' => $nb->id,     'name' => '990v6',                'price' => 11999, 'category' => 'footwear',    'stock' => 15, 'is_featured' => true],
            ['brand_id' => $nb->id,     'name' => 'Athletics Cap',        'price' => 1499,  'category' => 'accessories', 'stock' => 60, 'is_featured' => false],
        ];

        foreach ($products as $product) {
            Product::create(array_merge($product, [
                'description' => 'Premium quality product from ' . Brand::find($product['brand_id'])->name . '. Built for performance and style.',
            ]));
        }
    }
}