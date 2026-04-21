@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;gap:1rem;flex-wrap:wrap;">
        <div>
            <h1 class="font-display" style="font-size:2.5rem;color:#fff;">ADMIN DASHBOARD</h1>
            <p style="color:var(--muted);">Manage products, brands, and users.</p>
        </div>
        <span class="badge-admin" style="font-size:0.875rem;padding:0.4rem 1rem;">Admin</span>
        <a href="{{ route('admin.products.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Products</p>
                <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['products'] }}</p>
            </div>
        </a>
        <a href="{{ route('admin.products.archived') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#FFB347'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Archived</p>
                <p class="font-display" style="font-size:2.5rem;color:#FFB347;line-height:1;">{{ $stats['archived_products'] }}</p>
            </div>
        </a>
        <a href="{{ route('admin.brands.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Brands</p>
                <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['brands'] }}</p>
            </div>
        </a>
        <a href="{{ route('admin.orders.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#38BDF8'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Orders</p>
                <p class="font-display" style="font-size:2.5rem;color:#38BDF8;line-height:1;">{{ $stats['orders'] }}</p>
            </div>
        </a>
        <a href="{{ route('admin.coupons.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#FBBF24'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Coupons</p>
                <p class="font-display" style="font-size:2.5rem;color:#FBBF24;line-height:1;">0</p>
            </div>
        </a>
        <a href="{{ route('admin.sizes.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='#A78BFA'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Sizes</p>
                <p class="font-display" style="font-size:2.5rem;color:#A78BFA;line-height:1;">{{ $stats['sizes'] ?? 0 }}</p>
            </div>
        </a>
        <div class="card" style="padding:1.5rem;">
            <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Users</p>
            <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['users'] }}</p>
        </div>
        <div class="card" style="padding:1.5rem;">
            <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Featured</p>
            <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['featured'] }}</p>
        </div>
    </div>

    <div style="display:flex;gap:1rem;margin-bottom:2.5rem;flex-wrap:wrap;">
        <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="btn-accent">Manage Products & Brands</a>
        <a href="{{ route('admin.orders.index') }}" class="btn-outline">Manage Orders</a>
        <a href="{{ route('admin.coupons.index') }}" class="btn-outline">Manage Coupons</a>
        <a href="{{ route('admin.products.create') }}" class="btn-outline">+ Add Product</a>
        <a href="{{ route('admin.brands.create') }}" class="btn-outline">+ Add Brand</a>
        <a href="{{ route('admin.products.archived') }}" class="btn-outline">View Archived Products</a>
    </div>

    <div style="margin-bottom:2.5rem;">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
            <div>
                <h2 class="font-display" style="font-size:1.75rem;color:#fff;margin-bottom:0.5rem;">ADMIN MANAGEMENT</h2>
                <p style="color:var(--muted);margin:0;">Manage products and brands directly from the admin page.</p>
            </div>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <a href="{{ route('admin.products.create') }}" class="btn-accent">+ Add Product</a>
                <a href="{{ route('admin.brands.create') }}" class="btn-outline">+ Add Brand</a>
                <a href="{{ route('admin.products.archived') }}" class="btn-outline">Archived Products</a>
            </div>
        </div>

        <div style="margin-bottom:2rem;display:flex;border-bottom:1px solid var(--border);">
            <button onclick="showTab('products')" id="products-tab"
                    style="background:none;border:none;color:#fff;padding:1rem 2rem;cursor:pointer;border-bottom:3px solid {{ request('tab', 'products') === 'products' ? 'var(--accent)' : 'transparent' }};font-weight:600;">
                PRODUCTS
            </button>
            <button onclick="showTab('brands')" id="brands-tab"
                    style="background:none;border:none;color:#fff;padding:1rem 2rem;cursor:pointer;border-bottom:3px solid {{ request('tab', 'products') === 'brands' ? 'var(--accent)' : 'transparent' }};font-weight:600;">
                BRANDS
            </button>
        </div>

        <div id="products-content" style="display:{{ request('tab', 'products') === 'products' ? 'block' : 'none' }};">
            <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;align-items:start;">
                <aside>
                    <div class="card" style="padding:1.5rem;">
                        <h3 style="color:#fff;font-weight:700;margin-bottom:1.25rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Filter by Brand</h3>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['brand' => null, 'tab' => 'products'])) }}"
                               style="color:{{ !request('brand') ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ !request('brand') ? 'var(--accent)' : 'transparent' }};">
                                All Brands
                            </a>
                            @foreach($brands as $brand)
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['brand' => $brand->slug, 'tab' => 'products'])) }}"
                               style="color:{{ request('brand') === $brand->slug ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('brand') === $brand->slug ? 'var(--accent)' : 'transparent' }};">
                                {{ $brand->name }}
                            </a>
                            @endforeach
                        </div>

                        <h3 style="color:#fff;font-weight:700;margin:1.5rem 0 1rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Category</h3>
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            @foreach(['footwear','apparel','accessories'] as $cat)
                            <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['category' => $cat, 'tab' => 'products'])) }}"
                               style="color:{{ request('category') === $cat ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('category') === $cat ? 'var(--accent)' : 'transparent' }};text-transform:capitalize;">
                                {{ $cat }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <div>
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="margin-bottom:1.5rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="products">
                        @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." style="flex:1;min-width:220px;">
                        <button type="submit" class="btn-accent" style="border:none;cursor:pointer;white-space:nowrap;">Search</button>
                    </form>

                    @if($products->count())
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.25rem;">
                        @foreach($products as $product)
                        <div class="card" style="overflow:hidden;transition:all 0.2s;position:relative;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)';">
                            <div style="height:220px;overflow:hidden;position:relative;background:#1A1A1A;">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;">
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
                                <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;">
                                    <span style="color:var(--accent);font-weight:700;">₱{{ number_format($product->price,2) }}</span>
                                    <span style="color:{{ $product->stock > 0 ? 'var(--muted)' : '#FF6B6B' }};font-size:0.75rem;">{{ $product->stock > 0 ? $product->stock . ' left' : 'Out of Stock' }}</span>
                                </div>
                            </div>
                            <div style="padding:0.5rem 0.875rem;border-top:1px solid var(--border);background:#111;">
                                <div style="display:flex;gap:0.5rem;">
                                    <a href="{{ route('admin.products.edit', $product) }}" style="color:var(--accent);text-decoration:none;font-size:0.8rem;flex:1;text-align:center;padding:0.3rem;">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.archive', $product) }}" style="flex:1;" onsubmit="return confirm('Archive {{ $product->name }}? You can restore it later from Archived Products.')">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="background:none;border:none;color:#FFB347;cursor:pointer;font-size:0.8rem;width:100%;padding:0.3rem;">Archive</button>
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
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div id="brands-content" style="display:{{ request('tab', 'products') === 'brands' ? 'block' : 'none' }};">
            <div class="card" style="overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--border);background:#111;">
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
                            <td style="padding:1rem;color:var(--accent);font-weight:600;">{{ $brand->active_products_count }}</td>
                            <td style="padding:1rem;text-align:right;">
                                <div style="display:flex;gap:0.5rem;justify-content:flex-end;flex-wrap:wrap;">
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
</div>

<script>
function showTab(tab) {
    document.getElementById('products-content').style.display = tab === 'products' ? 'block' : 'none';
    document.getElementById('brands-content').style.display = tab === 'brands' ? 'block' : 'none';
    document.getElementById('products-tab').style.borderBottomColor = tab === 'products' ? 'var(--accent)' : 'transparent';
    document.getElementById('brands-tab').style.borderBottomColor = tab === 'brands' ? 'var(--accent)' : 'transparent';
}
</script>
@endsection