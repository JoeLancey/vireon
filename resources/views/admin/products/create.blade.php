@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
<div style="max-width:700px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('admin.products.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Products</a>
    <h1 class="font-display" style="font-size:2.5rem;color:#fff;margin:0.5rem 0 1.5rem;">ADD NEW PRODUCT</h1>

    <div class="card" style="padding:2rem;">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
            <div class="alert-error" style="margin-bottom:1.5rem;">
                @foreach($errors->all() as $error)<p style="margin:0.25rem 0;">{{ $error }}</p>@endforeach
            </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <div style="grid-column:1/-1;">
                    <label for="name">Product Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Air Max 270" required>
                </div>
                <div>
                    <label for="brand_id">Brand *</label>
                    <select id="brand_id" name="brand_id" required>
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="footwear"    {{ old('category') == 'footwear' ? 'selected' : '' }}>Footwear</option>
                        <option value="apparel"     {{ old('category') == 'apparel' ? 'selected' : '' }}>Apparel</option>
                        <option value="accessories" {{ old('category') == 'accessories' ? 'selected' : '' }}>Accessories</option>
                    </select>
                </div>
                <div>
                    <label for="price">Price (₱) *</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="0.00" step="0.01" min="0" required>
                </div>
                <div>
                    <label for="stock">Stock Quantity *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                </div>
                <div style="grid-column:1/-1;">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Product details...">{{ old('description') }}</textarea>
                </div>
                <div style="grid-column:1/-1;">
                    <label for="image">Product Image</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>

                {{-- Additional Images (INSIDE the form) --}}
                <div style="grid-column:1/-1;">
                    <label>Additional Images (up to 4)</label>
                    <input type="file" name="additional_images[]" multiple accept="image/*" style="padding:0.6rem;cursor:pointer;">
                    <p style="color:var(--muted);font-size:0.75rem;margin-top:0.3rem;">You can select up to 4 images</p>
                </div>

                {{-- Product Video --}}
                <div style="grid-column:1/-1;">
                    <label for="video">Product Video (MP4)</label>
                    <input type="file" id="video" name="video" accept="video/mp4,video/*">
                    <p style="color:var(--muted);font-size:0.75rem;margin-top:0.3rem;">Max file size: 100MB</p>
                </div>

                <div style="grid-column:1/-1;display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#1A1A1A;border:1px solid var(--border);border-radius:6px;">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="width:auto;padding:0;accent-color:var(--accent);">
                    <label for="is_featured" style="margin:0;color:#fff;cursor:pointer;">Mark as Featured Product</label>
                </div>
            </div>

            <div style="margin-top:2rem;display:flex;gap:1rem;">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;font-size:1rem;padding:0.75rem 2rem;">Add Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection