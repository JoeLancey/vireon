@extends('layouts.app')
@section('title', 'Products')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">

    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2rem;">
        <div>
            <h1 class="font-display" style="font-size:3rem;color:#fff;margin-bottom:0.25rem;">
                @if(!empty($selectedBrand)) {{ strtoupper($selectedBrand->name) }} @else ALL PRODUCTS @endif
            </h1>
            <p style="color:var(--muted);">{{ $products->total() }} products found</p>
        </div>
        @auth
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.products.create') }}" class="btn-accent">+ Add Product</a>
            @endif
        @endauth
    </div>

    <div style="display:grid;grid-template-columns:220px 1fr;gap:2rem;align-items:start;">

        {{-- Sidebar --}}
        <aside>
            <div class="card" style="padding:1.5rem;">
                <h3 style="color:#fff;font-weight:700;margin-bottom:1.25rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Filter by Brand</h3>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    <a href="{{ route('products.index') }}"
                       style="color:{{ !request('brand') ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ !request('brand') ? 'var(--accent)' : 'transparent' }};">
                        All Brands
                    </a>
                    @foreach($brands as $brand)
                    <a href="{{ route('products.index', array_merge(request()->all(), ['brand' => $brand->slug])) }}"
                       style="color:{{ request('brand') === $brand->slug ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('brand') === $brand->slug ? 'var(--accent)' : 'transparent' }};">
                        {{ $brand->name }}
                    </a>
                    @endforeach
                </div>

                <h3 style="color:#fff;font-weight:700;margin:1.5rem 0 1rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Category</h3>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    @foreach(['footwear','apparel','accessories'] as $cat)
                    <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat])) }}"
                       style="color:{{ request('category') === $cat ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('category') === $cat ? 'var(--accent)' : 'transparent' }};text-transform:capitalize;">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Products --}}
        <div>
            <form method="GET" action="{{ route('products.index') }}" style="margin-bottom:1.5rem;display:flex;gap:0.75rem;">
                @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;white-space:nowrap;">Search</button>
            </form>

            @if($products->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.25rem;">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" style="text-decoration:none;">
                    <div class="card" style="overflow:hidden;transition:all 0.2s;"
                         onmouseover="this.style.borderColor='var(--accent)'"
                         onmouseout="this.style.borderColor='var(--border)'">

                        {{-- Image fills the frame fully --}}
                        <div style="height:220px;overflow:hidden;position:relative;background:#1A1A1A;">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                    <span class="font-display" style="color:#333;font-size:2.5rem;">{{ substr($product->name,0,2) }}</span>
                                </div>
                            @endif
                            @if($product->is_featured)
                            <span style="position:absolute;top:0.5rem;left:0.5rem;background:var(--accent);color:#000;font-size:0.65rem;font-weight:700;padding:0.2rem 0.5rem;border-radius:3px;">FEATURED</span>
                            @endif
                            <span style="position:absolute;top:0.5rem;right:0.5rem;background:#00000088;color:#fff;font-size:0.65rem;font-weight:600;padding:0.2rem 0.5rem;border-radius:3px;text-transform:uppercase;">{{ $product->brand->name }}</span>
                        </div>

                        <div style="padding:0.875rem;">
                            <p style="color:var(--muted);font-size:0.7rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.2rem;">{{ $product->category }}</p>
                            <p style="color:#fff;font-weight:600;font-size:0.925rem;margin-bottom:0.5rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $product->name }}</p>
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span style="color:var(--accent);font-weight:700;">₱{{ number_format($product->price,2) }}</span>
                                <span style="color:{{ $product->stock > 0 ? 'var(--muted)' : '#FF6B6B' }};font-size:0.75rem;">{{ $product->stock > 0 ? $product->stock . ' left' : 'Out of Stock' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="margin-top:2rem;">{{ $products->links() }}</div>
            @else
            <div style="text-align:center;padding:4rem 2rem;color:var(--muted);">
                <p class="font-display" style="font-size:2rem;color:#333;margin-bottom:0.5rem;">NO PRODUCTS FOUND</p>
                <p>Try adjusting your filters or search term.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection