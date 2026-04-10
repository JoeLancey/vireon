<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller {
    public function index(Request $request) {
        $brands   = Brand::all();
        $query    = Product::with('brand');
        if ($request->brand_id) $query->where('brand_id', $request->brand_id);
        $products = $query->latest()->paginate(15);
        return view('admin.products.index', compact('products', 'brands'));
    }

    public function create() {
        $brands = Brand::all();
        return view('admin.products.create', compact('brands'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'brand_id'    => 'required|exists:brands,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|in:footwear,apparel,accessories',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $product = Product::create($validated);

        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product added successfully!');
    }

    public function edit(Product $product) {
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'brands'));
    }

    public function update(Request $request, Product $product) {
        $validated = $request->validate([
            'brand_id'    => 'required|exists:brands,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category'    => 'required|in:footwear,apparel,accessories',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $product->update($validated);

        if ($request->hasFile('additional_images')) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product) {
        if ($product->image) Storage::disk('public')->delete($product->image);
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }
        $name = $product->name;
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', '"' . $name . '" deleted successfully!');
    }

    public function show(Product $product) {
        return redirect()->route('admin.products.index');
    }
}