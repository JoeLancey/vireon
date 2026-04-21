<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index(Request $request) {
        $brands = Brand::all();
        $query  = Product::active()->with('brand');

        if ($request->brand) {
            $query->whereHas('brand', fn($q) => $q->where('slug', $request->brand));
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Price range filter
        if ($request->min_price) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        $query->latest();

        $products      = $query->paginate(12)->withQueryString();
        $selectedBrand = $request->brand ? Brand::where('slug', $request->brand)->first() : null;

        return view('products.index', compact('products', 'brands', 'selectedBrand'));
    }

    public function show(Product $product) {
        abort_if($product->is_archived, 404);

        $related = Product::active()
            ->with('brand')
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }
}