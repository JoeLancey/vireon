@extends('layouts.app')
@section('title', 'Order Confirmation')

@section('content')
<div style="max-width:1160px;margin:2rem auto;padding:0 1.5rem 3rem;">
    <div style="margin-bottom:1.4rem;padding:1rem;border:1px solid var(--border);border-radius:14px;background:linear-gradient(180deg,#141414,#101010);">
        <p style="color:#666;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.18em;margin:0 0 0.8rem;">Checkout Flow</p>
        <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0.7rem;">
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 1</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Bag</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 2</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Delivery</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 3</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Payment</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--accent);background:rgba(200,255,0,0.08);">
                <p style="margin:0;color:var(--accent);font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 4</p>
                <p style="margin:0.2rem 0 0;color:#fff;font-weight:600;font-size:0.88rem;">Confirm</p>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:1.5rem;align-items:start;">
        <section class="card" style="padding:1.5rem;border-radius:20px;">
            <p style="margin:0;color:var(--accent);font-size:0.74rem;letter-spacing:0.2em;text-transform:uppercase;font-weight:700;">Order Confirmed</p>
            <h1 class="font-display" style="margin:0.5rem 0 0.65rem;color:#fff;font-size:clamp(2rem,4.8vw,3.3rem);line-height:0.95;">THANK YOU, YOUR ORDER IS IN</h1>
            <p style="margin:0;color:#9a9a9a;line-height:1.7;max-width:46rem;">Your checkout was successful and stock is already reserved. We will keep you updated as soon as your order is prepared for delivery.</p>

            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.9rem;margin-top:1.2rem;">
                <div style="padding:0.9rem;border-radius:12px;background:#121212;border:1px solid var(--border);">
                    <p style="margin:0;color:#6e6e6e;font-size:0.74rem;letter-spacing:0.12em;text-transform:uppercase;">Order Number</p>
                    <p style="margin:0.45rem 0 0;color:#fff;font-weight:700;">{{ $confirmation['order_number'] }}</p>
                </div>
                <div style="padding:0.9rem;border-radius:12px;background:#121212;border:1px solid var(--border);">
                    <p style="margin:0;color:#6e6e6e;font-size:0.74rem;letter-spacing:0.12em;text-transform:uppercase;">Placed</p>
                    <p style="margin:0.45rem 0 0;color:#fff;font-weight:700;">{{ $confirmation['placed_at'] }}</p>
                </div>
                <div style="padding:0.9rem;border-radius:12px;background:#121212;border:1px solid var(--border);">
                    <p style="margin:0;color:#6e6e6e;font-size:0.74rem;letter-spacing:0.12em;text-transform:uppercase;">Delivery</p>
                    <p style="margin:0.45rem 0 0;color:#fff;font-weight:700;">{{ $confirmation['delivery_window'] }}</p>
                </div>
                <div style="padding:0.9rem;border-radius:12px;background:#121212;border:1px solid var(--border);">
                    <p style="margin:0;color:#6e6e6e;font-size:0.74rem;letter-spacing:0.12em;text-transform:uppercase;">ETA</p>
                    <p style="margin:0.45rem 0 0;color:#fff;font-weight:700;">{{ $confirmation['estimated_arrival'] }}</p>
                </div>
            </div>

            <div style="margin-top:1.2rem;padding:1rem;border-radius:14px;background:rgba(200,255,0,0.06);border:1px solid rgba(200,255,0,0.16);">
                <p style="margin:0 0 0.35rem;color:#fff;font-size:0.86rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;">Payment</p>
                <p style="margin:0;color:#d9e8a8;">{{ $confirmation['payment_method'] }}</p>
            </div>

            <div style="margin-top:1rem;padding:1rem;border-radius:14px;background:#121212;border:1px solid var(--border);">
                <p style="margin:0 0 0.35rem;color:#fff;font-size:0.86rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;">Shipping</p>
                <p style="margin:0;color:#9a9a9a;line-height:1.6;">{{ $confirmation['recipient_name'] }}<br>{{ $confirmation['shipping_address'] }}<br>{{ $confirmation['phone'] }}</p>
            </div>

            <div style="margin-top:1rem;padding:1rem;border-radius:14px;background:rgba(56,189,248,0.06);border:1px solid rgba(56,189,248,0.14);">
                <p style="margin:0 0 0.35rem;color:#fff;font-size:0.86rem;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;">Order Status</p>
                <p style="margin:0;color:#cfefff;">{{ $confirmation['status'] }}</p>
            </div>

            <div style="margin-top:1.3rem;display:grid;gap:0.75rem;">
                @foreach($confirmation['items'] as $item)
                <div style="display:grid;grid-template-columns:64px minmax(0,1fr) auto;gap:0.8rem;align-items:center;padding:0.75rem;border-radius:12px;border:1px solid var(--border);background:#111;">
                    <div style="width:64px;height:64px;border-radius:10px;overflow:hidden;background:#1A1A1A;">
                        @if($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#333;font-weight:700;">{{ strtoupper(substr($item['name'], 0, 2)) }}</div>
                        @endif
                    </div>
                    <div style="min-width:0;">
                        <p style="margin:0;color:#fff;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $item['name'] }}</p>
                        <p style="margin:0.25rem 0 0;color:#8f8f8f;font-size:0.8rem;">{{ $item['brand'] }} • Qty {{ $item['quantity'] }}</p>
                    </div>
                    <p style="margin:0;color:#fff;font-weight:700;">₱{{ number_format($item['subtotal'], 2) }}</p>
                </div>
                @endforeach
            </div>
        </section>

        <aside class="card" style="padding:1.35rem;border-radius:18px;position:sticky;top:88px;">
            <p style="margin:0 0 0.8rem;color:var(--accent);font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Summary</p>
            <div style="display:grid;gap:0.75rem;">
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Items</span>
                    <span>{{ $confirmation['items_count'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Subtotal</span>
                    <span>₱{{ number_format($confirmation['subtotal'], 2) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;color:#aaa;">
                    <span>Shipping</span>
                    <span>₱{{ number_format($confirmation['shipping_fee'], 2) }}</span>
                </div>
            </div>

            <div style="display:flex;justify-content:space-between;color:#fff;font-size:1.15rem;font-weight:700;margin-top:1rem;padding-top:0.9rem;border-top:1px solid var(--border);">
                <span>Total Paid</span>
                <span>₱{{ number_format($confirmation['total'], 2) }}</span>
            </div>

            <a href="{{ route('products.index') }}" class="btn-accent" style="display:block;text-align:center;margin-top:1.2rem;">Continue Shopping</a>
            @if(!empty($confirmation['order_id']))
            <a href="{{ route('orders.show', $confirmation['order_id']) }}" class="btn-outline" style="display:block;text-align:center;margin-top:0.7rem;">View This Order</a>
            @endif
            <a href="{{ route('orders.index') }}" class="btn-outline" style="display:block;text-align:center;margin-top:0.7rem;">My Orders</a>
            <a href="{{ route('cart.index') }}" class="btn-outline" style="display:block;text-align:center;margin-top:0.7rem;">Back to Cart</a>
        </aside>
    </div>
</div>
@endsection
