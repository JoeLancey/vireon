@extends('layouts.app')
@section('title', 'Manage Order')

@section('content')
<div class="page-container admin-orders-show-page" style="max-width:1160px;margin:2rem auto;padding:0 1.5rem 3rem;">
    <div class="admin-orders-header" style="display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;margin-bottom:1.3rem;">
        <div>
            <a href="{{ route('admin.orders.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;">← Back to Orders</a>
            <h1 class="font-display" style="font-size:clamp(2rem,4.6vw,3.4rem);line-height:0.95;margin:0.55rem 0 0;color:#fff;">{{ $order->order_number }}</h1>
            <p style="margin:0.55rem 0 0;color:#9a9a9a;">Placed {{ $order->placed_at->format('M d, Y h:i A') }}</p>
        </div>
        <span style="font-size:0.78rem;padding:0.35rem 0.7rem;border-radius:999px;background:{{ $order->status_color }}22;color:{{ $order->status_color }};border:1px solid {{ $order->status_color }}44;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;">{{ $order->status_label }}</span>
    </div>

    @if(session('success'))
        <div class="alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    <div class="admin-orders-show-layout" style="display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:1.2rem;align-items:start;">
        <section class="card" style="padding:1.2rem;border-radius:16px;">
            <div class="admin-order-info-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.8rem;margin-bottom:1rem;">
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Customer</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ $order->recipient_name }}</p>
                    <p style="margin:0.2rem 0 0;color:#8f8f8f;font-size:0.82rem;">{{ $order->user?->email }}</p>
                </div>
                <div style="padding:0.8rem;border-radius:10px;border:1px solid var(--border);background:#111;">
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Contact</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;">{{ $order->phone }}</p>
                </div>
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
                    <p style="margin:0;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Shipping Address</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-weight:600;line-height:1.6;">{{ $order->address_line1 }}@if($order->address_line2), {{ $order->address_line2 }}@endif<br>{{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}<br>{{ $order->country }}</p>
                </div>
            </div>

            <div style="display:grid;gap:0.75rem;">
                @foreach($order->items as $item)
                <div class="admin-order-item" style="display:grid;grid-template-columns:64px minmax(0,1fr) auto;gap:0.75rem;align-items:center;padding:0.7rem;border:1px solid var(--border);border-radius:12px;background:#101010;">
                    <div style="width:64px;height:64px;border-radius:10px;overflow:hidden;background:#1A1A1A;">
                        @if($item->product_image)
                            <img src="{{ Storage::url($item->product_image) }}" alt="{{ $item->product_name }}" style="width:100%;height:100%;object-fit:cover;">
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

        <aside class="card admin-order-sidebar" style="padding:1.2rem;border-radius:14px;position:sticky;top:88px;">
            <p style="margin:0;color:var(--accent);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Update Order</p>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" style="display:grid;gap:0.9rem;margin-top:0.9rem;">
                @csrf
                @method('PATCH')
                <div>
                    <label style="display:block;margin-bottom:0.35rem;color:var(--muted);font-size:0.78rem;">Status</label>
                    <select name="status">
                        @foreach(['pending','confirmed','processing','shipped','delivered','closed','cancelled'] as $status)
                        <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:0.35rem;color:var(--muted);font-size:0.78rem;">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Optional tracking code">
                </div>
                <button type="submit" class="btn-accent" style="border:none;cursor:pointer;width:100%;">Save Changes</button>
            </form>

            <div style="display:grid;gap:0.75rem;margin-top:1.2rem;">
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Subtotal</span>
                    <span>₱{{ number_format((float) $order->subtotal, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Shipping</span>
                    <span>₱{{ number_format((float) $order->shipping_fee, 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#fff;font-size:1.1rem;font-weight:700;padding-top:0.9rem;border-top:1px solid var(--border);">
                    <span>Total</span>
                    <span>₱{{ number_format((float) $order->total, 2) }}</span>
                </div>
                <div style="padding:0.95rem;border-radius:12px;background:#111;border:1px solid var(--border);">
                    <p style="margin:0 0 0.3rem;color:#777;font-size:0.72rem;letter-spacing:0.12em;text-transform:uppercase;">Tracking</p>
                    <p style="margin:0;color:#fff;font-weight:600;">{{ $order->tracking_number ?? 'Not assigned yet' }}</p>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection