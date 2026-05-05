@extends('layouts.app')
@section('title', 'Archived Products')

@section('content')
<div class="page-container admin-archived-page" style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">
    <div class="admin-archived-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
        <div>
            <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.12em;margin-bottom:0.5rem;">Admin / Products</p>
            <h1 class="font-display" style="font-size:2.5rem;color:#fff;margin-bottom:0.5rem;">ARCHIVED PRODUCTS</h1>
            <p style="color:var(--muted);max-width:700px;">Archived products are hidden from the storefront. You can restore them anytime or permanently delete them here.</p>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
            <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="btn-outline">Back to Dashboard</a>
            <a href="{{ route('admin.products.create') }}" class="btn-accent">+ Add Product</a>
        </div>
    </div>

    <div class="admin-archived-layout" style="display:grid;grid-template-columns:240px 1fr;gap:2rem;align-items:start;">
        <aside>
            <div class="card" style="padding:1.5rem;">
                <h3 style="color:#fff;font-weight:700;margin-bottom:1.25rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Filter by Brand</h3>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    <a href="{{ route('admin.products.archived', array_merge(request()->query(), ['brand' => null])) }}"
                       style="color:{{ !request('brand') ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ !request('brand') ? 'var(--accent)' : 'transparent' }};">
                        All Brands
                    </a>
                    @foreach($brands as $brand)
                    <a href="{{ route('admin.products.archived', array_merge(request()->query(), ['brand' => $brand->slug])) }}"
                       style="color:{{ request('brand') === $brand->slug ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('brand') === $brand->slug ? 'var(--accent)' : 'transparent' }};">
                        {{ $brand->name }}
                    </a>
                    @endforeach
                </div>

                <h3 style="color:#fff;font-weight:700;margin:1.5rem 0 1rem;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.1em;">Category</h3>
                <div style="display:flex;flex-direction:column;gap:0.5rem;">
                    <a href="{{ route('admin.products.archived', array_merge(request()->query(), ['category' => null])) }}"
                       style="color:{{ !request('category') ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ !request('category') ? 'var(--accent)' : 'transparent' }};">
                        All Categories
                    </a>
                    @foreach(['footwear','apparel','accessories'] as $cat)
                    <a href="{{ route('admin.products.archived', array_merge(request()->query(), ['category' => $cat])) }}"
                       style="color:{{ request('category') === $cat ? 'var(--accent)' : '#aaa' }};text-decoration:none;padding:0.4rem 0 0.4rem 0.75rem;font-size:0.9rem;border-left:2px solid {{ request('category') === $cat ? 'var(--accent)' : 'transparent' }};text-transform:capitalize;">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <div>
            <form method="GET" action="{{ route('admin.products.archived') }}" class="admin-archived-search" style="margin-bottom:1.5rem;display:flex;gap:0.75rem;flex-wrap:wrap;">
                @if(request('brand'))<input type="hidden" name="brand" value="{{ request('brand') }}">@endif
                @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search archived products..." style="flex:1;min-width:220px;">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;white-space:nowrap;">Search</button>
            </form>

            @if($products->count())
            <div class="card admin-archived-table-wrap" style="overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--border);background:#111;">
                            <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Product</th>
                            <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Brand</th>
                            <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Category</th>
                            <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Price</th>
                            <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Stock</th>
                            <th style="padding:1rem;text-align:right;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr style="border-bottom:1px solid var(--border);" onmouseover="this.style.background='#1A1A1A'" onmouseout="this.style.background='transparent'">
                            <td style="padding:1rem;">
                                <div style="display:flex;align-items:center;gap:0.9rem;">
                                    <div style="width:56px;height:56px;border-radius:8px;overflow:hidden;background:#111;flex-shrink:0;">
                                        @if($product->image)
                                            <img src="{{ storage_asset_url($product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                <span class="font-display" style="color:#333;font-size:1.2rem;">{{ substr($product->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p style="color:#fff;font-weight:600;margin:0 0 0.2rem;">{{ $product->name }}</p>
                                        @if($product->is_featured)
                                            <span style="display:inline-block;background:#FFB34722;color:#FFB347;border:1px solid #FFB34744;font-size:0.65rem;font-weight:700;padding:0.15rem 0.45rem;border-radius:999px;">Featured</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding:1rem;color:#fff;">{{ $product->brand->name }}</td>
                            <td style="padding:1rem;color:var(--muted);text-transform:capitalize;">{{ $product->category }}</td>
                            <td style="padding:1rem;color:var(--accent);font-weight:700;">₱{{ number_format($product->price, 2) }}</td>
                            <td style="padding:1rem;color:{{ $product->stock > 0 ? 'var(--muted)' : '#FF6B6B' }};">{{ $product->stock > 0 ? $product->stock : 'Out of stock' }}</td>
                            <td style="padding:1rem;text-align:right;">
                                <div style="display:flex;gap:0.5rem;justify-content:flex-end;flex-wrap:wrap;">
                                    <form method="POST" action="{{ route('admin.products.unarchive', $product) }}" onsubmit="return confirm('Restore {{ $product->name }} to the live product list?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" style="background:#1E1E1E;border:1px solid var(--border);color:#fff;padding:0.4rem 0.85rem;border-radius:4px;cursor:pointer;font-size:0.8rem;">Unarchive</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.force-destroy', $product) }}" onsubmit="return confirm('Permanently delete {{ $product->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:none;border:1px solid #FF6B6B33;color:#FF6B6B;padding:0.4rem 0.85rem;border-radius:4px;cursor:pointer;font-size:0.8rem;">Delete Permanently</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top:2rem;">{{ $products->links() }}</div>
            @else
            <div class="card" style="padding:3rem 2rem;text-align:center;">
                <p class="font-display" style="font-size:2rem;color:#333;margin-bottom:0.5rem;">NO ARCHIVED PRODUCTS</p>
                <p style="color:var(--muted);margin-bottom:1.5rem;">Archived items will appear here once products are removed from the active admin list.</p>
                <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="btn-outline">Back to Active Products</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection