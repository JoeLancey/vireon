<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        $brands = Brand::select(['id', 'name', 'slug', 'logo', 'accent_color'])
            ->withCount(['products' => fn ($query) => $query->available()])
            ->get();

        $featured = Product::available()
            ->select(['id', 'brand_id', 'name', 'price', 'image', 'stock'])
            ->with('brand:id,name')
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $allProducts = Product::available()
            ->select(['id', 'brand_id', 'name', 'price', 'image', 'stock'])
            ->with('brand:id,name')
            ->latest()
            ->limit(12)
            ->get();

        $productStats = Product::available()
            ->selectRaw('COUNT(*) as total_products')
            ->selectRaw('SUM(CASE WHEN stock > 0 THEN 1 ELSE 0 END) as in_stock')
            ->selectRaw('SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock')
            ->first();

        $stats = [
            'total_products' => (int) ($productStats->total_products ?? 0),
            'in_stock'       => (int) ($productStats->in_stock ?? 0),
            'out_of_stock'   => (int) ($productStats->out_of_stock ?? 0),
            'total_brands'   => Brand::count(),
        ];

        // Pre-build hero data with URLs
        $heroData = $featured->take(4)->map(fn($p) => [
            'name'  => strtoupper($p->name),
            'price' => '₱' . number_format($p->price, 2),
            'brand' => strtoupper($p->brand?->name ?? 'VIREON'),
            'url'   => route('products.show', $p),
        ])->values();

        return view('home.index', compact('brands', 'featured', 'allProducts', 'stats', 'heroData'));
    }
}