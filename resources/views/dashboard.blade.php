@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">
    <div style="margin-bottom:2rem;">
        <h1 class="font-display" style="font-size:2.5rem;color:#fff;">
            WELCOME BACK, <span style="color:var(--accent);">{{ strtoupper(auth()->user()->name) }}</span>
        </h1>
        <p style="color:var(--muted);">Browse our latest collections from top brands.</p>
    </div>

    {{-- Brand Quick Links --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;margin-bottom:3rem;">
        @foreach($brands as $brand)
        <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;text-align:center;transition:all 0.2s;"
                 onmouseover="this.style.borderColor='{{ $brand->accent_color }}';this.style.transform='translateY(-3px)'"
                 onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                <div style="width:45px;height:45px;border-radius:50%;background:{{ $brand->accent_color }}22;border:2px solid {{ $brand->accent_color }};display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                    <span class="font-display" style="color:{{ $brand->accent_color }};font-size:1rem;">{{ substr($brand->name,0,2) }}</span>
                </div>
                <span class="font-display" style="font-size:1.25rem;color:#fff;">{{ $brand->name }}</span>
                <p style="color:var(--muted);font-size:0.8rem;margin-top:0.25rem;">{{ $brand->products_count }} items</p>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Featured Products --}}
    <h2 class="font-display" style="font-size:1.75rem;color:#fff;margin-bottom:1.25rem;">FEATURED FOR YOU</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;">
        @foreach($featured as $product)
        <a href="{{ route('products.show', $product) }}" style="text-decoration:none;">
            <div class="card" style="overflow:hidden;transition:all 0.2s;"
                 onmouseover="this.style.borderColor='var(--accent)'"
                 onmouseout="this.style.borderColor='var(--border)'">
                <div style="background:#1A1A1A;height:160px;display:flex;align-items:center;justify-content:center;">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="max-height:140px;max-width:100%;object-fit:contain;">
                    @else
                        <span class="font-display" style="color:#333;font-size:2.5rem;">{{ substr($product->name,0,2) }}</span>
                    @endif
                </div>
                <div style="padding:0.875rem;">
                    <p style="color:var(--muted);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">{{ $product->brand->name }}</p>
                    <p style="color:#fff;font-weight:600;font-size:0.9rem;">{{ $product->name }}</p>
                    <p style="color:var(--accent);font-weight:700;margin-top:0.5rem;">₱{{ number_format($product->price,2) }}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Browse All --}}
    <div style="text-align:center;margin-top:3rem;">
        <a href="{{ route('products.index') }}" class="btn-accent" style="font-size:1rem;padding:0.875rem 2.5rem;">Browse All Products</a>
    </div>
</div>
@endsection