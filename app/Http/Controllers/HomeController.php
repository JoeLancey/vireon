<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        $brands      = Brand::withCount(['products' => fn ($query) => $query->available()])->get();
        $featured    = Product::available()->with('brand')->where('is_featured', true)->take(6)->get();
        $allProducts = Product::available()->with('brand')->latest()->limit(12)->get();
        $stats       = [
            'total_products' => Product::available()->count(),
            'in_stock'       => Product::available()->where('stock', '>', 0)->count(),
            'out_of_stock'   => Product::available()->where('stock', 0)->count(),
            'total_brands'   => Brand::count(),
        ];

        // Pre-build hero data with URLs
        $heroData = $featured->take(4)->map(fn($p) => [
            'name'  => strtoupper($p->name),
            'price' => '₱' . number_format($p->price, 2),
            'brand' => strtoupper($p->brand->name),
            'url'   => route('products.show', $p),
        ])->values();

        return view('home.index', compact('brands', 'featured', 'allProducts', 'stats', 'heroData'));
    }
}