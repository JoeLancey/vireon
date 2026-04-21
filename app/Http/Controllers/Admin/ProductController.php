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
        return redirect()->route('admin.dashboard', $request->query());
    }

    public function archived(Request $request)
    {
        $query = Product::archived()->with('brand');

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->brand) {
            $brand = Brand::where('slug', $request->brand)->first();

            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.archived', compact('products', 'brands'));
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
            'video'       => 'nullable|file|mimes:mp4|mimetypes:video/mp4,video/quicktime,video/x-m4v|max:102400',
            'is_featured' => 'boolean',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('products/videos', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_archived'] = false;
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
            'video'       => 'nullable|file|mimes:mp4|mimetypes:video/mp4,video/quicktime,video/x-m4v|max:102400',
            'is_featured' => 'boolean',
            'additional_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('video')) {
            if ($product->video) Storage::disk('public')->delete($product->video);
            $validated['video'] = $request->file('video')->store('products/videos', 'public');
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
        $product->update(['is_archived' => true]);

        return redirect()->route('admin.products.index')
            ->with('success', '"' . $product->name . '" archived successfully!');
    }

    public function unarchive(Product $product)
    {
        $product->update(['is_archived' => false]);

        return redirect()->route('admin.products.archived')
            ->with('success', '"' . $product->name . '" restored successfully!');
    }

    public function forceDestroy(Product $product)
    {
        if (! $product->is_archived) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Only archived products can be permanently deleted.');
        }

        if ($product->image) Storage::disk('public')->delete($product->image);
        if ($product->video) Storage::disk('public')->delete($product->video);
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.archived')
            ->with('success', '"' . $name . '" permanently deleted successfully!');
    }

    public function show(Product $product) {
        return redirect()->route('admin.products.index');
    }
}