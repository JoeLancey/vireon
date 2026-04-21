@extends('layouts.app')
@section('title', 'Admin Orders')

@section('content')
<div style="max-width:1240px;margin:0 auto;padding:2rem 1.5rem 3rem;">
    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
        <div>
            <p style="color:var(--muted);font-size:0.82rem;letter-spacing:0.08em;text-transform:uppercase;margin:0 0 0.45rem;">Admin</p>
            <h1 class="font-display" style="font-size:clamp(2.2rem,5vw,3.6rem);color:#fff;line-height:0.95;margin:0;">ORDERS</h1>
            <p style="color:#9a9a9a;margin:0.65rem 0 0;max-width:44rem;line-height:1.7;">Monitor order status, update fulfillment progress, and keep customer deliveries moving.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-outline">Back to Dashboard</a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:0.85rem;margin-bottom:1.4rem;">
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Total</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:var(--accent);font-size:2rem;">{{ $stats['total'] }}</p>
        </div>
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Pending</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:#fff;font-size:2rem;">{{ $stats['pending'] }}</p>
        </div>
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Processing</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:#38BDF8;font-size:2rem;">{{ $stats['processing'] }}</p>
        </div>
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Shipped</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:#FBBF24;font-size:2rem;">{{ $stats['shipped'] }}</p>
        </div>
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Delivered</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:#22C55E;font-size:2rem;">{{ $stats['delivered'] }}</p>
        </div>
        <div class="card" style="padding:1rem;">
            <p style="margin:0;color:var(--muted);font-size:0.72rem;text-transform:uppercase;letter-spacing:0.12em;">Cancelled</p>
            <p class="font-display" style="margin:0.35rem 0 0;color:#FF6B6B;font-size:2rem;">{{ $stats['cancelled'] }}</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:1.25rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order, customer, or email" style="flex:1;min-width:240px;">
        <select name="status" style="min-width:180px;">
            <option value="">All Statuses</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $status)
            <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-accent" style="border:none;cursor:pointer;">Filter</button>
    </form>

    <div class="card" style="overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#111;border-bottom:1px solid var(--border);">
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Order</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Customer</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Status</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Total</th>
                    <th style="padding:1rem;text-align:left;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Placed</th>
                    <th style="padding:1rem;text-align:right;color:var(--muted);font-size:0.75rem;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom:1px solid var(--border);">
                    <td style="padding:1rem;">
                        <div style="display:flex;flex-direction:column;gap:0.25rem;">
                            <span style="color:#fff;font-weight:600;">{{ $order->order_number }}</span>
                            <span style="color:#8f8f8f;font-size:0.82rem;">{{ $order->items_count }} item(s)</span>
                        </div>
                    </td>
                    <td style="padding:1rem;color:#fff;">
                        <div style="display:flex;flex-direction:column;gap:0.25rem;">
                            <span>{{ $order->recipient_name }}</span>
                            <span style="color:#8f8f8f;font-size:0.82rem;">{{ $order->user?->email }}</span>
                        </div>
                    </td>
                    <td style="padding:1rem;">
                        <span style="font-size:0.72rem;padding:0.22rem 0.55rem;border-radius:999px;background:{{ $order->status_color }}22;color:{{ $order->status_color }};border:1px solid {{ $order->status_color }}44;text-transform:uppercase;letter-spacing:0.08em;font-weight:700;">{{ $order->status_label }}</span>
                    </td>
                    <td style="padding:1rem;color:#fff;font-weight:700;">₱{{ number_format((float) $order->total, 2) }}</td>
                    <td style="padding:1rem;color:#8f8f8f;">{{ $order->placed_at->format('M d, Y h:i A') }}</td>
                    <td style="padding:1rem;text-align:right;">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline" style="text-decoration:none;display:inline-block;">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:2rem;text-align:center;color:var(--muted);">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1.5rem;">{{ $orders->links() }}</div>
</div>
@endsection