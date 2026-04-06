<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\User;

class DashboardController extends Controller {
    public function index() {
        if (auth()->user()->isAdmin()) {
            $stats = [
                'products' => Product::count(),
                'brands'   => Brand::count(),
                'users'    => User::count(),
                'featured' => Product::where('is_featured', true)->count(),
            ];
            $recentProducts = Product::with('brand')->latest()->take(5)->get();
            return view('admin.dashboard', compact('stats', 'recentProducts'));
        }
        $brands   = Brand::withCount('products')->get();
        $featured = Product::with('brand')->where('is_featured', true)->take(4)->get();
        return view('dashboard', compact('brands', 'featured'));
    }
}