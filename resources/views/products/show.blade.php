@extends('layouts.app')
@section('title', $product->name)

@section('content')
<div style="max-width:1100px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('products.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Products</a>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;margin-top:2rem;align-items:start;">
        {{-- Image --}}
        <div style="background:#161616;border:1px solid var(--border);border-radius:12px;padding:2rem;min-height:350px;display:flex;align-items:center;justify-content:center;">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="max-height:300px;max-width:100%;object-fit:contain;">
            @else
                <span class="font-display" style="color:#2A2A2A;font-size:5rem;">{{ substr($product->name,0,2) }}</span>
            @endif
        </div>

        {{-- Details --}}
        <div>
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;flex-wrap:wrap;">
                <span style="background:{{ $product->brand->accent_color }}22;color:{{ $product->brand->accent_color }};border:1px solid {{ $product->brand->accent_color }}44;padding:0.25rem 0.75rem;border-radius:4px;font-size:0.8rem;font-weight:700;text-transform:uppercase;">
                    {{ $product->brand->name }}
                </span>
                <span style="color:var(--muted);font-size:0.8rem;text-transform:capitalize;">{{ $product->category }}</span>
                @if($product->is_featured)
                <span style="background:var(--accent)22;color:var(--accent);border:1px solid var(--accent)44;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:600;">FEATURED</span>
                @endif
            </div>

            <h1 style="font-size:2rem;font-weight:700;color:#fff;margin-bottom:1rem;line-height:1.2;">{{ $product->name }}</h1>
            <p class="font-display" style="font-size:2.5rem;color:var(--accent);margin-bottom:1rem;">₱{{ number_format($product->price,2) }}</p>
            <p style="color:{{ $product->stock > 0 ? '#4ADE80' : '#FF6B6B' }};font-size:0.875rem;font-weight:600;margin-bottom:1.5rem;">
                {{ $product->stock > 0 ? '✓ In Stock (' . $product->stock . ' available)' : '✗ Out of Stock' }}
            </p>

            @if($product->description)
            <p style="color:#aaa;line-height:1.7;margin-bottom:2rem;">{{ $product->description }}</p>
            @endif

            @auth
                @if(!auth()->user()->isAdmin())
                <button class="btn-accent" style="border:none;cursor:pointer;font-size:1rem;padding:0.875rem 2.5rem;width:100%;{{ $product->stock < 1 ? 'opacity:0.4;cursor:not-allowed;' : '' }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                    Add to Cart
                </button>
                @else
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-accent" style="display:block;text-align:center;padding:0.875rem;">Edit Product</a>
                @endif
            @else
            <a href="{{ route('login') }}" class="btn-accent" style="display:block;text-align:center;padding:0.875rem;font-size:1rem;">Login to Purchase</a>
            @endauth
        </div>
    </div>

    {{-- Related Products --}}
    @if($related->count())
    <div style="margin-top:4rem;">
        <h2 class="font-display" style="font-size:1.75rem;color:#fff;margin-bottom:1.5rem;">MORE FROM {{ strtoupper($product->brand->name) }}</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;">
            @foreach($related as $rel)
            <a href="{{ route('products.show', $rel) }}" style="text-decoration:none;">
                <div class="card" style="overflow:hidden;transition:all 0.2s;"
                     onmouseover="this.style.borderColor='var(--accent)'"
                     onmouseout="this.style.borderColor='var(--border)'">
                    <div style="background:#1A1A1A;height:140px;display:flex;align-items:center;justify-content:center;">
                        <span class="font-display" style="color:#333;font-size:2rem;">{{ substr($rel->name,0,2) }}</span>
                    </div>
                    <div style="padding:0.75rem;">
                        <p style="color:#fff;font-size:0.875rem;font-weight:500;margin-bottom:0.3rem;">{{ $rel->name }}</p>
                        <p style="color:var(--accent);font-weight:700;">₱{{ number_format($rel->price,2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection