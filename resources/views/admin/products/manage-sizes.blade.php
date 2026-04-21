@extends('layouts.app')

@section('content')
<div style="max-width:1000px;margin:2rem auto;padding:0 1.5rem;">
    <a href="{{ route('admin.products.edit', $product) }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Product</a>

    <h1 class="font-display" style="font-size:2.2rem;color:#fff;margin:0.7rem 0 0.3rem;">MANAGE SIZE STOCK</h1>
    <p style="color:var(--muted);margin:0 0 1.5rem;">{{ $product->name }}</p>
    <p style="color:var(--muted);margin:-0.75rem 0 1.5rem;font-size:0.85rem;">
        Category rule: <span style="color:var(--accent);font-weight:600;">{{ ucfirst($product->category) }}</span>
        products can only use <span style="color:var(--accent);font-weight:600;">{{ ucfirst($allowedCategory ?? '') }}</span> sizes.
    </p>

    <div class="card" style="padding:1.5rem;">
        <form action="{{ route('admin.products.update-sizes', $product) }}" method="POST" style="display:grid;gap:1rem;">
            @csrf
            @method('PATCH')

            @foreach($allSizes as $category => $sizes)
                <section style="border:1px solid var(--border);border-radius:12px;padding:1rem;background:#141414;">
                    <h2 style="color:#fff;font-size:1rem;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.9rem;">{{ $category }}</h2>

                    <div style="display:grid;gap:0.65rem;">
                        @foreach($sizes as $size)
                            @php
                                $assignedSize = $product->sizes->firstWhere('id', $size->id);
                                $isAssigned = (bool) $assignedSize;
                                $sizeStock = $assignedSize?->pivot?->stock ?? 0;
                            @endphp
                            <div style="display:grid;grid-template-columns:minmax(0,1fr) 180px;gap:0.75rem;align-items:center;padding:0.75rem;border:1px solid var(--border);border-radius:10px;background:#101010;">
                                <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;color:#fff;">
                                    <input type="checkbox"
                                           name="sizes[]"
                                           value="{{ $size->id }}"
                                           @checked($isAssigned)
                                           style="width:auto;accent-color:var(--accent);"
                                           onchange="toggleSizeStock('{{ $size->id }}', this.checked)">
                                    <span>{{ $size->name }} <span style="color:var(--muted);">({{ $size->value }})</span></span>
                                </label>

                                <div>
                                    <label for="stock-{{ $size->id }}" style="display:block;color:var(--muted);font-size:0.75rem;margin-bottom:0.35rem;">Stock Qty</label>
                                    <input id="stock-{{ $size->id }}"
                                           type="number"
                                           min="0"
                                           name="stock[{{ $size->id }}]"
                                           value="{{ $sizeStock }}"
                                           {{ $isAssigned ? '' : 'disabled' }}>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-top:0.5rem;">
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;">Save Size Stock</button>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleSizeStock(sizeId, enabled) {
        const input = document.getElementById('stock-' + sizeId);
        if (!input) {
            return;
        }

        input.disabled = !enabled;
        if (!enabled) {
            input.value = 0;
        }
    }
</script>
@endsection
