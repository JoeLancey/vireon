@extends('layouts.app')
@section('title', 'Admin Management')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">

    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2rem;">
        <div>
            <h1 class="font-display" style="font-size:3rem;color:#fff;margin-bottom:0.25rem;">ADMIN MANAGEMENT</h1>
            <p style="color:var(--muted);">Manage products and brands</p>
        </div>
        <div style="display:flex;gap:1rem;">
            <a href="{{ route('admin.products.create') }}" class="btn-accent">+ Add Product</a>
            <a href="{{ route('admin.brands.create') }}" class="btn-outline">+ Add Brand</a>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div style="margin-bottom:2rem;">
        <div style="display:flex;border-bottom:1px solid var(--border);">
            <button onclick="showTab('products')" id="products-tab"
                    style="background:none;border:none;color:#fff;padding:1rem 2rem;cursor:pointer;border-bottom:3px solid {{ request('tab', 'products') === 'products' ? 'var(--accent)' : 'transparent' }};font-weight:600;">
                PRODUCTS
            </button>
            <button onclick="showTab('brands')" id="brands-tab"
                    style="background:none;border:none;color:#fff;padding:1rem 2rem;cursor:pointer;border-bottom:3px solid {{ request('tab', 'products') === 'brands' ? 'var(--accent)' : 'transparent' }};font-weight:600;">
                BRANDS
            </button>
        </div>
    </div>

    {{-- Products Tab --}}
    <div id="products-content" style="display:{{ request('tab', 'products') === 'products' ? 'block' : 'none' }};">

        <div style="display:grid;grid-template-columns:220px 1fr;gap:2rem;align-items:start;">

            {{-- Sidebar --}}
            <aside>
                <div class="card" style="padding:1.5rem;">
                    <h3 style="color:#fff;font-weight:700;margin-bottom:1.25rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Filter by Brand</h3>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <a href="{{ route('admin.products.index', ['tab' => 'products']) }}"
                           style="color:{{ !request('brand') ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ !request('brand') ? 'var(--accent)' : 'transparent' }};">
                            All Brands
                        </a>
                        @foreach($brands as $brand)
                        <a href="{{ route('admin.products.index', array_merge(request()->all(), ['brand' => $brand->slug, 'tab' => 'products'])) }}"
                           style="color:{{ request('brand') === $brand->slug ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('brand') === $brand->slug ? 'var(--accent)' : 'transparent' }};">
                            {{ $brand->name }}
                        </a>
                        @endforeach
                    </div>

                    <h3 style="color:#fff;font-weight:700;margin:1.5rem 0 1rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Category</h3>
                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        @foreach(['footwear','apparel','accessories'] as $cat)
                        <a href="{{ route('admin.products.index', array_merge(request()->all(), ['category' => $cat, 'tab' => 'products'])) }}"
                           style="color:{{ request('category') === $cat ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('category') === $cat ? 'var(--accent)' : 'transparent' }};text-transform:capitalize;">
                            {{ $cat }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>

        {{-- Products --}}
        <div>
            <form method="GET" action="{{ route('admin.products.index') }}" style="margin-bottom:1.5rem;display:flex;gap:0.75rem;">
                <input type="hidden" name="tab" value="products">
                @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;white-space:nowrap;">Search</button>
            </form>

            @if($products->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.25rem;">
                @foreach($products as $product)
                <div class="card" style="overflow:hidden;transition:all 0.2s;position:relative;"
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

                    {{-- Admin Actions --}}
                    <div style="padding:0.5rem 0.875rem;border-top:1px solid var(--border);background:#111;">
                        <div style="display:flex;gap:0.5rem;">
                            <a href="{{ route('admin.products.edit', $product) }}" style="color:var(--accent);text-decoration:none;font-size:0.8rem;flex:1;text-align:center;padding:0.3rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="flex:1;" onsubmit="return confirm('Delete {{ $product->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#FF6B6B;cursor:pointer;font-size:0.8rem;width:100%;padding:0.3rem;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:2rem;">{{ $products->links() }}</div>
            @else
            <div style="text-align:center;padding:4rem 2rem;color:var(--muted);">
                <p class="font-display" style="font-size:2rem;color:#333;margin-bottom:0.5rem;">NO PRODUCTS FOUND</p>
                <p>Try adjusting your filters or search term.</p>
            @endif
        </div>
    </div>
    </div>

    {{-- Brands Tab --}}
    <div id="brands-content" style="display:{{ request('tab', 'products') === 'brands' ? 'block' : 'none' }};">
        <div class="card" style="overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid var(--border);">
                        <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Brand</th>
                        <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Slug</th>
                        <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Color</th>
                        <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Products</th>
                        <th style="padding:1rem;text-align:right;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr style="border-bottom:1px solid var(--border);" onmouseover="this.style.background='#1A1A1A'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div style="width:36px;height:36px;border-radius:50%;background:{{ $brand->accent_color }}22;border:2px solid {{ $brand->accent_color }};display:flex;align-items:center;justify-content:center;overflow:hidden;">
                                    @if($brand->logo)
                                        <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }} logo" style="width:100%;height:100%;object-fit:contain;">
                                    @else
                                        <span class="font-display" style="color:{{ $brand->accent_color }};font-size:0.8rem;">{{ substr($brand->name,0,2) }}</span>
                                    @endif
                                </div>
                                <span style="color:#fff;font-weight:600;">{{ $brand->name }}</span>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--muted);font-size:0.875rem;font-family:monospace;">{{ $brand->slug }}</td>
                        <td style="padding:1rem;">
                            <div style="display:flex;align-items:center;gap:0.5rem;">
                                <div style="width:20px;height:20px;border-radius:3px;background:{{ $brand->accent_color }};"></div>
                                <span style="color:var(--muted);font-size:0.8rem;font-family:monospace;">{{ $brand->accent_color }}</span>
                            </div>
                        </td>
                        <td style="padding:1rem;color:var(--accent);font-weight:600;">{{ $brand->products_count }}</td>
                        <td style="padding:1rem;text-align:right;">
                            <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                <a href="{{ route('admin.brands.edit', $brand) }}" style="background:#1E1E1E;border:1px solid var(--border);color:#fff;padding:0.3rem 0.8rem;border-radius:4px;text-decoration:none;font-size:0.8rem;">Edit</a>
                                <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Delete {{ $brand->name }}? All its products will also be deleted.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:1px solid #FF6B6B33;color:#FF6B6B;padding:0.3rem 0.8rem;border-radius:4px;cursor:pointer;font-size:0.8rem;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:2rem;text-align:center;color:var(--muted);">
                            <p class="font-display" style="font-size:1.5rem;color:#333;margin-bottom:0.5rem;">NO BRANDS FOUND</p>
                            <p>Start by adding your first brand.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
function showTab(tab) {
    // Hide all content
    document.getElementById('products-content').style.display = 'none';
    document.getElementById('brands-content').style.display = 'none';

    // Remove active tab styling
    document.getElementById('products-tab').style.borderBottomColor = 'transparent';
    document.getElementById('brands-tab').style.borderBottomColor = 'transparent';

    // Show selected content and activate tab
    document.getElementById(tab + '-content').style.display = 'block';
    document.getElementById(tab + '-tab').style.borderBottomColor = 'var(--accent)';

    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tab);
    window.history.pushState({}, '', url);
}

// Handle browser back/forward buttons
window.addEventListener('popstate', function() {
    const url = new URL(window.location);
    const tab = url.searchParams.get('tab') || 'products';
    showTab(tab);
});
</script>
@endsection