<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index(Request $request) {
        $brands = Brand::all();
        $query  = Product::with('brand');

        if ($request->brand) {
            $query->whereHas('brand', fn($q) => $q->where('slug', $request->brand));
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products      = $query->latest()->paginate(12);
        $selectedBrand = $request->brand ? Brand::where('slug', $request->brand)->first() : null;

        return view('products.index', compact('products', 'brands', 'selectedBrand'));
    }

    public function show(Product $product) {
        $related = Product::with('brand')
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        return view('products.show', compact('product', 'related'));
    }
}