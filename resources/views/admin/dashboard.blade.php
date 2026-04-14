@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div style="max-width:1200px;margin:0 auto;padding:2rem 1.5rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
        <div>
            <h1 class="font-display" style="font-size:2.5rem;color:#fff;">ADMIN DASHBOARD</h1>
            <p style="color:var(--muted);">Manage products, brands, and users.</p>
        </div>
        <span class="badge-admin" style="font-size:0.875rem;padding:0.4rem 1rem;">⚡ Admin</span>
    </div>

    {{-- Stats --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2.5rem;">
        <a href="{{ route('admin.products.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Products</p>
                <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['products'] }}</p>
            </div>
        </a>
        <a href="{{ route('admin.brands.index') }}" style="text-decoration:none;">
            <div class="card" style="padding:1.5rem;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
                <p style="color:var(--muted);font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;">Brands</p>
                <p class="font-display" style="font-size:2.5rem;color:var(--accent);line-height:1;">{{ $stats['brands'] }}</p>
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

    {{-- Quick Actions --}}
    <div style="display:flex;gap:1rem;margin-bottom:2.5rem;flex-wrap:wrap;">
        <a href="{{ route('admin.products.index') }}" class="btn-accent">📦 Manage Products & Brands</a>
        <a href="{{ route('admin.products.create') }}" class="btn-outline">+ Add Product</a>
        <a href="{{ route('admin.brands.create') }}" class="btn-outline">+ Add Brand</a>
    </div>

    {{-- Database Seeding --}}
    <div style="margin-bottom:2.5rem;">
        <h2 class="font-display" style="font-size:1.5rem;color:#fff;margin-bottom:1rem;">DATABASE SEEDING</h2>
        <div class="card" style="padding:1.5rem;">
            <p style="color:var(--muted);margin-bottom:1rem;">Quickly populate your database with sample data for testing and development.</p>
            <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.seed.brands') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-accent" onclick="return confirm('Seed sample brands?')">🌱 Seed Brands</button>
                </form>
                <form method="POST" action="{{ route('admin.seed.products') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-outline" onclick="return confirm('Seed sample products?')">📦 Seed Products</button>
                </form>
            </div>
            @if(session('success'))
                <div style="margin-top:1rem;padding:0.75rem;border-radius:0.375rem;background:#10B981;color:#fff;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="margin-top:1rem;padding:0.75rem;border-radius:0.375rem;background:#EF4444;color:#fff;">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Products --}}
    <h2 class="font-display" style="font-size:1.75rem;color:#fff;margin-bottom:1rem;">RECENT PRODUCTS</h2>
    <div class="card" style="overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid var(--border);background:#111;">
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Product</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Brand</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Price</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Stock</th>
                    <th style="padding:1rem;text-align:right;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentProducts as $product)
                <tr style="border-bottom:1px solid var(--border);" onmouseover="this.style.background='#1A1A1A'" onmouseout="this.style.background='transparent'">
                    <td style="padding:1rem;color:#fff;font-weight:500;">{{ $product->name }}</td>
                    <td style="padding:1rem;color:var(--muted);">{{ $product->brand->name }}</td>
                    <td style="padding:1rem;color:var(--accent);font-weight:600;">₱{{ number_format($product->price,2) }}</td>
                    <td style="padding:1rem;color:{{ $product->stock < 5 ? '#FF6B6B' : '#aaa' }};">{{ $product->stock }}</td>
                    <td style="padding:1rem;text-align:right;">
                        <a href="{{ route('admin.products.edit', $product) }}" style="color:var(--accent);text-decoration:none;font-size:0.875rem;margin-right:1rem;">Edit</a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display:inline;" onsubmit="return confirm('Delete {{ $product->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#FF6B6B;cursor:pointer;font-size:0.875rem;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection