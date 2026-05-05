@extends('layouts.app')
@section('title', 'Manage Brands')

@section('content')
<div class="page-container admin-brands-page" style="max-width:1000px;margin:2rem auto;padding:0 1.5rem;">
    <div class="admin-brands-header" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
        <h1 class="font-display" style="font-size:2.5rem;color:#fff;">MANAGE BRANDS</h1>
        <a href="{{ route('admin.brands.create') }}" class="btn-accent">+ Add Brand</a>
    </div>

    <div class="card admin-brands-table-wrap" style="overflow:hidden;">
        <table class="admin-brands-table" style="width:100%;border-collapse:collapse;">
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
                <tr><td colspan="5" style="padding:2rem;text-align:center;color:var(--muted);">No brands yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top:1rem;">{{ $brands->links() }}</div>
</div>
@endsection