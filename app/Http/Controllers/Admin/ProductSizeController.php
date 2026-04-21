<?php
namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSizeController extends Controller
{
    protected function categorySizeMap(): array
    {
        return [
            'apparel' => 'clothing',
            'footwear' => 'footwear',
            'accessories' => 'accessories',
        ];
    }

    protected function allowedSizeCategory(Product $product): string
    {
        return $this->categorySizeMap()[$product->category] ?? 'clothing';
    }

    public function manageSizes(Product $product)
    {
        $allowedCategory = $this->allowedSizeCategory($product);

        $product->load(['sizes' => fn ($query) => $query->where('category', $allowedCategory)]);

        $allSizes = Size::where('category', $allowedCategory)
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('admin.products.manage-sizes', compact('product', 'allSizes', 'allowedCategory'));
    }

    public function updateSizes(Request $request, Product $product)
    {
        $allowedCategory = $this->allowedSizeCategory($product);

        $validated = $request->validate([
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['integer', 'exists:sizes,id'],
            'stock' => ['nullable', 'array'],
            'stock.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $selectedSizes = $validated['sizes'] ?? [];
        $stockInput = $validated['stock'] ?? [];

        // Error catch: prevent assigning sizes from the wrong category.
        if (! empty($selectedSizes)) {
            $invalidSizeExists = Size::whereIn('id', $selectedSizes)
                ->where('category', '!=', $allowedCategory)
                ->exists();

            if ($invalidSizeExists) {
                return back()
                    ->withInput()
                    ->with('error', 'Selected sizes do not match the product category.');
            }
        }

        $syncPayload = [];
        foreach ($selectedSizes as $sizeId) {
            $syncPayload[$sizeId] = [
                'stock' => (int) ($stockInput[$sizeId] ?? 0),
            ];
        }

        $product->sizes()->sync($syncPayload);
        $product->update([
            'stock' => array_sum(array_map(fn ($item) => (int) $item['stock'], $syncPayload)),
        ]);

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product sizes updated successfully.');
    }
}
