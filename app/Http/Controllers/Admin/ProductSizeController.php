<?php
namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSizeController extends Controller
{
    public function manageSizes(Product $product)
    {
        $product->load('sizes');
        $allSizes = Size::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('admin.products.manage-sizes', compact('product', 'allSizes'));
    }

    public function updateSizes(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['integer', 'exists:sizes,id'],
        ]);

        $product->sizes()->sync($validated['sizes'] ?? []);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product sizes updated successfully.');
    }
}
