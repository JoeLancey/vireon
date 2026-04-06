@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
<div style="max-width:700px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('admin.products.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Products</a>
    <h1 class="font-display" style="font-size:2.5rem;color:#fff;margin:0.5rem 0 0.25rem;">EDIT PRODUCT</h1>
    <p style="color:var(--muted);margin-bottom:1.5rem;">{{ $product->name }}</p>

    <div class="card" style="padding:2rem;">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="alert-error" style="margin-bottom:1.5rem;">
                @foreach($errors->all() as $error)<p style="margin:0.25rem 0;">{{ $error }}</p>@endforeach
            </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <div style="grid-column:1/-1;">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div>
                    <label for="brand_id">Brand *</label>
                    <select id="brand_id" name="brand_id" required>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="footwear"    {{ old('category', $product->category) == 'footwear' ? 'selected' : '' }}>Footwear</option>
                        <option value="apparel"     {{ old('category', $product->category) == 'apparel' ? 'selected' : '' }}>Apparel</option>
                        <option value="accessories" {{ old('category', $product->category) == 'accessories' ? 'selected' : '' }}>Accessories</option>
                    </select>
                </div>
                <div>
                    <label for="price">Price (₱) *</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                </div>
                <div>
                    <label for="stock">Stock Quantity *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                </div>
                <div style="grid-column:1/-1;">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
                <div style="grid-column:1/-1;">
                    @if($product->image)
                    <label>Current Image</label>
                    <div style="background:#1A1A1A;border:1px solid var(--border);border-radius:6px;padding:1rem;display:inline-block;margin-bottom:0.75rem;">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="max-height:120px;max-width:200px;object-fit:contain;">
                    </div>
                    @endif
                    <label for="image">{{ $product->image ? 'Replace Image' : 'Product Image' }}</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <div style="grid-column:1/-1;display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#1A1A1A;border:1px solid var(--border);border-radius:6px;">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="width:auto;padding:0;accent-color:var(--accent);">
                    <label for="is_featured" style="margin:0;color:#fff;cursor:pointer;">Featured Product</label>
                </div>
            </div>

            <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;font-size:1rem;padding:0.75rem 2rem;">Save Changes</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="margin-left:auto;" onsubmit="return confirm('Permanently delete this product?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:1px solid #FF6B6B44;color:#FF6B6B;padding:0.75rem 1.5rem;border-radius:4px;cursor:pointer;">Delete Product</button>
                </form>
            </div>
        </form>
    </div>
</div>
@endsection