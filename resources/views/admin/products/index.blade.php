@extends('layouts.app')
@section('title', 'Manage Products')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
        <div>
            <h1 class="font-display" style="font-size:2.5rem;color:#fff;">MANAGE PRODUCTS</h1>
            <p style="color:var(--muted);">{{ $products->total() }} total products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-accent">+ Add Product</a>
    </div>

    <form method="GET" style="margin-bottom:1.5rem;">
        <select name="brand_id" onchange="this.form.submit()" style="max-width:200px;">
            <option value="">All Brands</option>
            @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="card" style="overflow:hidden;">
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
                @forelse($products as $product)
                <tr style="border-bottom:1px solid var(--border);" onmouseover="this.style.background='#1A1A1A'" onmouseout="this.style.background='transparent'">
                    <td style="padding:1rem;">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:40px;height:40px;background:#1E1E1E;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);">
                                <span style="color:#aaa;font-size:0.75rem;font-weight:700;">{{ substr($product->name,0,2) }}</span>
                            </div>
                            <div>
                                <p style="color:#fff;font-weight:500;margin:0;">{{ $product->name }}</p>
                                @if($product->is_featured)
                                <span style="background:var(--accent);color:#000;font-size:0.6rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:2px;">FEATURED</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="padding:1rem;color:#aaa;">{{ $product->brand->name }}</td>
                    <td style="padding:1rem;color:#aaa;text-transform:capitalize;">{{ $product->category }}</td>
                    <td style="padding:1rem;color:var(--accent);font-weight:600;">₱{{ number_format($product->price,2) }}</td>
                    <td style="padding:1rem;color:{{ $product->stock < 5 ? '#FF6B6B' : '#aaa' }};">{{ $product->stock }}</td>
                    <td style="padding:1rem;text-align:right;">
                        <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                            <a href="{{ route('admin.products.edit', $product) }}" style="background:#1E1E1E;border:1px solid var(--border);color:#fff;padding:0.35rem 0.85rem;border-radius:4px;text-decoration:none;font-size:0.8rem;">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete \'{{ $product->name }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#1E1E1E;border:1px solid #FF6B6B33;color:#FF6B6B;padding:0.35rem 0.85rem;border-radius:4px;cursor:pointer;font-size:0.8rem;">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:3rem;text-align:center;color:var(--muted);">
                        No products found. <a href="{{ route('admin.products.create') }}" style="color:var(--accent);">Add one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1.5rem;">{{ $products->links() }}</div>
</div>
@endsection