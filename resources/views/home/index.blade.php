@extends('layouts.app')
@section('title', 'Home — Premium Wearable Brands')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;">

    {{-- Hero --}}
    <section style="padding:5rem 0 4rem;text-align:center;">
        <p style="color:var(--accent);font-size:0.85rem;letter-spacing:0.2em;text-transform:uppercase;font-weight:600;margin-bottom:1rem;">
            OFFICIAL PARTNER PLATFORM
        </p>
        <h1 class="font-display" style="font-size:clamp(3.5rem,8vw,7rem);line-height:0.95;color:#fff;margin-bottom:1.5rem;">
            GEAR UP.<br><span style="color:var(--accent);">STAND OUT.</span>
        </h1>
        <p style="color:#aaa;max-width:500px;margin:0 auto 2.5rem;line-height:1.7;">
            VIREON is your official partner for premium wearable brands.
            Shop Nike, Adidas, New Balance, Puma and more — all in one place.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('products.index') }}" class="btn-accent" style="font-size:1rem;padding:0.75rem 2rem;">Shop All Products</a>
            @guest
            <a href="{{ route('register') }}" class="btn-outline" style="font-size:1rem;padding:0.75rem 2rem;">Create Account</a>
            @endguest
        </div>
    </section>

    {{-- Brands --}}
    <section style="padding:3rem 0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
            <h2 class="font-display" style="font-size:2.5rem;color:#fff;">OUR BRANDS</h2>
            <span style="color:var(--muted);font-size:0.875rem;">{{ $brands->count() }} partner brands</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
            @foreach($brands as $brand)
            <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" style="text-decoration:none;">
                <div class="card" style="padding:1.5rem;text-align:center;transition:all 0.2s;display:block;"
                     onmouseover="this.style.borderColor='{{ $brand->accent_color }}';this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                    <div style="width:50px;height:50px;border-radius:50%;background:{{ $brand->accent_color }}22;border:2px solid {{ $brand->accent_color }};display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
                        <span class="font-display" style="color:{{ $brand->accent_color }};font-size:1.1rem;">{{ substr($brand->name,0,2) }}</span>
                    </div>
                    <p class="font-display" style="color:#fff;font-size:1.1rem;margin:0 0 0.25rem;">{{ $brand->name }}</p>
                    <p style="color:var(--muted);font-size:0.75rem;">{{ $brand->products_count }} products</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- Featured --}}
    @if($featured->count())
    <section style="padding:3rem 0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
            <h2 class="font-display" style="font-size:2.5rem;color:#fff;">FEATURED DROPS</h2>
            <a href="{{ route('products.index') }}" style="color:var(--accent);text-decoration:none;font-size:0.875rem;font-weight:500;">View All →</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1.5rem;">
            @foreach($featured as $product)
            <a href="{{ route('products.show', $product) }}" style="text-decoration:none;">
                <div class="card" style="overflow:hidden;transition:all 0.2s;"
                     onmouseover="this.style.borderColor='var(--accent)';this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.borderColor='var(--border)';this.style.transform='translateY(0)'">
                    <div style="background:#1E1E1E;height:200px;display:flex;align-items:center;justify-content:center;">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="max-height:180px;max-width:100%;object-fit:contain;">
                        @else
                            <span class="font-display" style="color:#333;font-size:3rem;">{{ substr($product->name,0,2) }}</span>
                        @endif
                    </div>
                    <div style="padding:1rem;">
                        <p style="color:var(--muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.25rem;">{{ $product->brand->name }}</p>
                        <p style="color:#fff;font-weight:600;margin-bottom:0.5rem;">{{ $product->name }}</p>
                        <p style="color:var(--accent);font-weight:700;font-size:1.1rem;">₱{{ number_format($product->price,2) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection