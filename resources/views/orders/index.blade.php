@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div style="max-width:1160px;margin:2rem auto;padding:0 1.5rem 3rem;">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <div>
            <p style="color:var(--muted);font-size:0.82rem;letter-spacing:0.08em;text-transform:uppercase;margin:0 0 0.45rem;">Account</p>
            <h1 class="font-display" style="font-size:clamp(2.3rem,5vw,3.8rem);color:#fff;line-height:0.95;margin:0;">MY ORDERS</h1>
            <p style="color:#9a9a9a;margin:0.6rem 0 0;max-width:45rem;line-height:1.7;">Track all your completed checkouts in one place, including delivery method, payment method, and order totals.</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-outline">Continue Shopping</a>
    </div>

    @if($orders->isEmpty())
        <div class="card" style="padding:3rem 2rem;text-align:center;border-radius:18px;">
            <p class="font-display" style="color:#fff;font-size:2rem;margin:0 0 0.5rem;">NO ORDERS YET</p>
            <p style="color:#9a9a9a;max-width:32rem;margin:0 auto 1.5rem;line-height:1.7;">Once you complete checkout, your orders will appear here with full item and payment details.</p>
            <a href="{{ route('products.index') }}" class="btn-accent">Start Shopping</a>
        </div>
    @else
        <div style="display:grid;gap:0.95rem;">
            @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="card" style="text-decoration:none;padding:1rem 1.2rem;border-radius:14px;display:grid;grid-template-columns:minmax(0,1fr) auto;gap:1rem;align-items:center;">
                <div>
                    <div style="display:flex;align-items:center;gap:0.7rem;flex-wrap:wrap;">
                        <span style="font-size:0.75rem;letter-spacing:0.12em;text-transform:uppercase;color:var(--accent);font-weight:700;">{{ $order->order_number }}</span>
                        <span style="font-size:0.75rem;color:#777;">{{ $order->placed_at->format('M d, Y h:i A') }}</span>
                        <span style="font-size:0.72rem;padding:0.22rem 0.55rem;border-radius:999px;background:{{ $order->status_color }}22;color:{{ $order->status_color }};border:1px solid {{ $order->status_color }}44;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;">{{ $order->status_label }}</span>
                    </div>
                    <p style="margin:0.45rem 0 0;color:#fff;font-weight:600;">{{ $order->delivery_window_label }} • {{ $order->payment_method_label }}</p>
                    <p style="margin:0.25rem 0 0;color:#8f8f8f;font-size:0.86rem;">{{ $order->items_count }} item(s) • ETA {{ optional($order->estimated_arrival)->format('M d, Y') }}</p>
                </div>
                <div style="text-align:right;">
                    <p style="margin:0;color:#7f7f7f;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Total Paid</p>
                    <p style="margin:0.35rem 0 0;color:#fff;font-size:1.15rem;font-weight:700;">₱{{ number_format((float) $order->total, 2) }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div style="margin-top:1.5rem;">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
