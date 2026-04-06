<?php
namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;

class HomeController extends Controller {
    public function index() {
        $brands   = Brand::withCount('products')->get();
        $featured = Product::with('brand')->where('is_featured', true)->take(6)->get();
        return view('home.index', compact('brands', 'featured'));
    }
}