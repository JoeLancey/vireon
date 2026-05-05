@extends('layouts.app')
@section('title', 'Edit Brand')

@section('content')
<div style="max-width:600px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('admin.brands.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back</a>
    <h1 class="font-display" style="font-size:2.5rem;color:#fff;margin:0.5rem 0 1.5rem;">EDIT BRAND</h1>

    <div class="card" style="padding:2rem;">
        <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="alert-error" style="margin-bottom:1.5rem;">
                @foreach($errors->all() as $error)<p style="margin:0.25rem 0;">{{ $error }}</p>@endforeach
            </div>
            @endif

            <div style="display:flex;flex-direction:column;gap:1.25rem;">
                <div>
                    <label for="name">Brand Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3">{{ old('description', $brand->description) }}</textarea>
                </div>
                <div>
                    <label for="accent_color">Brand Accent Color</label>
                    <div style="display:flex;gap:0.75rem;align-items:center;">
                        <input type="color" id="accent_color" name="accent_color" value="{{ old('accent_color', $brand->accent_color) }}" style="width:60px;height:42px;padding:2px;cursor:pointer;">
                        <input type="text" id="accent_color_text" value="{{ old('accent_color', $brand->accent_color) }}" style="max-width:120px;" oninput="document.getElementById('accent_color').value=this.value">
                    </div>
                </div>
                <div>
                    <label for="logo">Brand Logo</label>
                    <input type="file" id="logo" name="logo" accept="image/png,image/jpeg,image/webp">
                    @if($brand->logo)
                    <div style="margin-top:0.75rem;display:flex;align-items:center;gap:0.75rem;">
                        <img src="{{ storage_asset_url($brand->logo) }}" alt="{{ $brand->name }} logo" style="width:48px;height:48px;border-radius:8px;object-fit:contain;border:1px solid var(--border);">
                        <span style="color:#aaa;font-size:0.9rem;">Current logo</span>
                    </div>
                    @endif
                    <p style="color:#777;font-size:0.85rem;margin:0.5rem 0 0;">Upload a square or round logo for the brand card.</p>
                </div>
            </div>

            <div style="margin-top:2rem;display:flex;gap:1rem;">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;padding:0.75rem 2rem;font-size:1rem;">Save Changes</button>
                <a href="{{ route('admin.brands.index') }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection