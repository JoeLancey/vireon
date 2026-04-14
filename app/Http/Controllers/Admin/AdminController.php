<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'brands' => Brand::count(),
            'users' => User::count(),
            'featured' => Product::where('is_featured', true)->count(),
        ];

        $recentProducts = Product::with('brand')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts'));
    }

    public function seedBrands()
    {
        $brands = [
            ['name' => 'Nike',         'description' => 'Just Do It — Performance athletic wear.',       'accent_color' => '#FF6B00'],
            ['name' => 'Adidas',       'description' => 'Impossible is Nothing — Iconic 3-stripe brand.','accent_color' => '#000000'],
            ['name' => 'New Balance',  'description' => 'Fearlessly Independent Since 1906.',            'accent_color' => '#CF3338'],
            ['name' => 'Puma',         'description' => 'Forever Faster — Sports & lifestyle brand.',    'accent_color' => '#FFD700'],
            ['name' => 'Under Armour', 'description' => 'Protect This House — Performance gear.',        'accent_color' => '#E8002D'],
            ['name' => 'Converse',     'description' => 'All Star — Iconic canvas sneakers.',            'accent_color' => '#DC143C'],
        ];

        $count = 0;
        foreach ($brands as $brand) {
            $existing = Brand::where('slug', Str::slug($brand['name']))->first();
            if (!$existing) {
                Brand::create([
                    'name'         => $brand['name'],
                    'slug'         => Str::slug($brand['name']),
                    'description'  => $brand['description'],
                    'accent_color' => $brand['accent_color'],
                ]);
                $count++;
            }
        }

        return redirect()->back()->with('success', "Successfully seeded {$count} brands!");
    }

    public function seedProducts()
    {
        $nike    = Brand::where('name', 'Nike')->first();
        $adidas  = Brand::where('name', 'Adidas')->first();
        $nb      = Brand::where('name', 'New Balance')->first();

        if (!$nike || !$adidas || !$nb) {
            return redirect()->back()->with('error', 'Please seed brands first before seeding products!');
        }

        $products = [
            ['brand_id' => $nike->id,   'name' => 'Air Max 270',          'price' => 8999,  'category' => 'footwear',    'stock' => 25, 'is_featured' => true],
            ['brand_id' => $nike->id,   'name' => 'Dri-FIT Running Tee',  'price' => 1999,  'category' => 'apparel',     'stock' => 50, 'is_featured' => false],
            ['brand_id' => $nike->id,   'name' => 'Pro Training Shorts',  'price' => 2499,  'category' => 'apparel',     'stock' => 40, 'is_featured' => false],
            ['brand_id' => $adidas->id, 'name' => 'Ultraboost 22',        'price' => 9999,  'category' => 'footwear',    'stock' => 20, 'is_featured' => true],
            ['brand_id' => $adidas->id, 'name' => 'Tiro 23 Training Pants','price' => 3499, 'category' => 'apparel',     'stock' => 35, 'is_featured' => false],
            ['brand_id' => $nb->id,     'name' => '990v6',                'price' => 11999, 'category' => 'footwear',    'stock' => 15, 'is_featured' => true],
            ['brand_id' => $nb->id,     'name' => 'Athletics Cap',        'price' => 1499,  'category' => 'accessories', 'stock' => 60, 'is_featured' => false],
        ];

        $count = 0;
        foreach ($products as $product) {
            $existing = Product::where('name', $product['name'])->first();
            if (!$existing) {
                Product::create(array_merge($product, [
                    'description' => 'Premium quality product from ' . Brand::find($product['brand_id'])->name . '. Built for performance and style.',
                ]));
                $count++;
            }
        }

        return redirect()->back()->with('success', "Successfully seeded {$count} products!");
    }
}