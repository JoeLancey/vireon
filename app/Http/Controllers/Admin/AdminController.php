<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\User;
use App\Models\Order;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $stats = [
            'products' => Product::active()->count(),
            'archived_products' => Product::archived()->count(),
            'brands' => Brand::count(),
            'users' => User::count(),
            'featured' => Product::active()->where('is_featured', true)->count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'sizes' => Size::count(),
        ];

        $brands = Brand::withCount([
            'products',
            'products as active_products_count' => fn ($query) => $query->active(),
        ])->get();

        $query = Product::active()->with('brand');

        if ($request->brand) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $recentProducts = Product::active()
            ->with('brand')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'brands', 'products'));
    }

}