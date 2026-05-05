@extends('layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="page-container order-show-page" style="max-width:1160px;margin:2rem auto;padding:0 1.5rem 3rem;">
    <div class="orders-header" style="display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.2rem;">
        <div>
            <a href="{{ route('orders.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to My Orders</a>
            <h1 class="font-display" style="font-size:clamp(2rem,4.6vw,3.4rem);line-height:0.95;margin:0.55rem 0 0;color:#fff;">ORDER {{ $order->order_number }}</h1>
            <p style="margin:0.55rem 0 0;color:#9a9a9a;">Placed {{ $order->placed_at->format('M d, Y h:i A') }}</p>
        </div>
        <div style="display:flex;gap:0.65rem;flex-wrap:wrap;align-items:center;">
            <span style="font-size:0.78rem;padding:0.35rem 0.7rem;border-radius:999px;background:{{ $order->status_color }}22;color:{{ $order->status_color }};border:1px solid {{ $order->status_color }}44;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;">{{ $order->status_label }}</span>
            @if($order->canBeCancelled())
            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-outline" style="background:none;cursor:pointer;border-color:#FF6B6B44;color:#FF6B6B;">Cancel Order</button>
            </form>
            @endif
            <a href="{{ route('products.index') }}" class="btn-outline">Buy Again</a>
        </div>
    </div>

    <div class="orders-detail-layout" style="display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:1.3rem;align-items:start;">
        <section class="card" style="padding:1.2rem;border-radius:16px;">
            <div class="order-metrics" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.8rem;margin-bottom:1rem;">
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Delivery</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ $order->delivery_window_label }}</p>
                </div>
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Payment</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ $order->payment_method_label }}</p>
                </div>
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Items</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ $order->items_count }}</p>
                </div>
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">ETA</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ optional($order->estimated_arrival)->format('M d, Y') }}</p>
                </div>
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;grid-column:1/-1;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Ship To</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;line-height:1.6;">{{ $order->recipient_name }}<br>{{ $order->address_line1 }}@if($order->address_line2), {{ $order->address_line2 }}@endif<br>{{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}<br>{{ $order->country }}<br>{{ $order->phone }}</p>
                </div>
            </div>

            <div style="display:grid;gap:0.75rem;">
                @foreach($order->items as $item)
                <div class="order-item" style="display:grid;grid-template-columns:64px minmax(0,1fr) auto;gap:0.75rem;align-items:center;padding:0.7rem;border:1px solid var(--border);border-radius:12px;background:#101010;">
                    <div style="width:64px;height:64px;border-radius:10px;overflow:hidden;background:#1A1A1A;">
                        @if($item->product_image)
                            <img src="{{ storage_asset_url($item->product_image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#333;font-weight:700;">{{ strtoupper(substr($item->product_name, 0, 2)) }}</div>
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <p style="margin:0;color:#fff;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item->product_name }}</p>
                        <p style="margin:0.22rem 0 0;color:#8d8d8d;font-size:0.82rem;">{{ $item->brand_name ?? 'Brand' }} • {{ ucfirst($item->category ?? 'product') }} • Qty {{ $item->quantity }}</p>
                    </div>
                    <p style="margin:0;color:#fff;font-weight:700;">₱{{ number_format((float) $item->subtotal, 2) }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <aside class="card order-summary-card" style="padding:1.2rem;border-radius:14px;position:sticky;top:88px;">
            <p style="margin:0;color:var(--accent);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Payment Summary</p>
            <div style="display:grid;gap:0.75rem;margin-top:0.75rem;">
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Subtotal</span>
                    <span>₱{{ number_format((float) $order->subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Shipping</span>
                    <span>₱{{ number_format((float) $order->shipping_fee, 2) }}</span>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;color:#fff;font-size:1.1rem;font-weight:700;margin-top:0.9rem;padding-top:0.9rem;border-top:1px solid var(--border);">
                <span>Total Paid</span>
                <span>₱{{ number_format((float) $order->total, 2) }}</span>
            </div>

            <div style="margin-top:1rem;padding:0.95rem;border-radius:12px;background:#111;border:1px solid var(--border);">
                <p style="margin:0 0 0.3rem;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Tracking</p>
                <p style="margin:0;color:#fff;font-weight:600;">{{ $order->tracking_number ?? 'Not assigned yet' }}</p>
            </div>
        </aside>
    </div>
</div>
@endsection
