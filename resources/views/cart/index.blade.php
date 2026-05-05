@extends('layouts.app')
@section('title', 'Your Cart')

@section('content')
@php
    $subtotal = (float) $cart->items->sum(fn ($item) => $item->subtotal());
    $expressFee = 249.00;
@endphp
<div class="page-container cart-page" style="max-width:1180px;margin:2rem auto;padding:0 1.5rem 3rem;">
    <div class="cart-step-panel" style="margin-bottom:1.4rem;padding:1rem;border:1px solid var(--border);border-radius:14px;background:linear-gradient(180deg,#141414,#101010);">
        <p style="color:#666;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.18em;margin:0 0 0.8rem;">Checkout Flow</p>
        <div class="cart-step-grid" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:0.7rem;">
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--accent);background:rgba(200,255,0,0.08);">
                <p style="margin:0;color:var(--accent);font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 1</p>
                <p style="margin:0.2rem 0 0;color:#fff;font-weight:600;font-size:0.88rem;">Bag</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 2</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Delivery</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 3</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Payment</p>
            </div>
            <div style="padding:0.65rem 0.7rem;border-radius:10px;border:1px solid var(--border);background:#121212;">
                <p style="margin:0;color:#666;font-size:0.7rem;letter-spacing:0.14em;text-transform:uppercase;">Step 4</p>
                <p style="margin:0.2rem 0 0;color:#b5b5b5;font-weight:600;font-size:0.88rem;">Confirm</p>
            </div>
        </div>
    </div>

    <div class="cart-header" style="display:flex;justify-content:space-between;align-items:flex-end;gap:1rem;flex-wrap:wrap;margin-bottom:2rem;">
        <div>
            <a href="{{ route('products.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;letter-spacing:0.04em;">← Continue Shopping</a>
            <h1 class="font-display" style="font-size:clamp(2.2rem,5vw,3.8rem);color:#fff;margin:0.65rem 0 0;line-height:0.95;">YOUR CART</h1>
            <p style="color:#9a9a9a;margin:0.65rem 0 0;max-width:46rem;line-height:1.7;">Review your items, adjust quantities, and move to checkout without leaving the store flow.</p>
        </div>

        @if($cart->items->count())
        <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-outline" style="background:none;cursor:pointer;">Clear Cart</button>
        </form>
        @endif
    </div>

    @if($errors->any())
        <div class="alert-error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($cart->items->isEmpty())
        <div class="card" style="padding:3rem 2rem;text-align:center;border-radius:18px;">
            <p class="font-display" style="color:#fff;font-size:2rem;margin:0 0 0.5rem;">YOUR CART IS EMPTY</p>
            <p style="color:#9a9a9a;max-width:30rem;margin:0 auto 1.5rem;line-height:1.7;">Browse the latest products and build a checkout list that stays ready until you’re done.</p>
            <a href="{{ route('products.index') }}" class="btn-accent">Browse Products</a>
        </div>
    @else
        <div class="cart-layout" style="display:grid;grid-template-columns:minmax(0,1fr) 360px;gap:1.5rem;align-items:start;">
            <div class="cart-items" style="display:grid;gap:1rem;">
                @foreach($cart->items as $item)
                <div class="card cart-item" style="padding:1rem;border-radius:18px;display:grid;grid-template-columns:110px minmax(0,1fr) auto;gap:1rem;align-items:center;">
                    <a href="{{ route('products.show', $item->product) }}" style="display:block;width:110px;height:110px;background:#1A1A1A;border-radius:14px;overflow:hidden;flex-shrink:0;">
                        @if($item->product->image)
                            <img src="{{ storage_asset_url($item->product->image) }}" alt="{{ $item->product->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <span class="font-display" style="color:#333;font-size:2rem;">{{ substr($item->product->name, 0, 2) }}</span>
                            </div>
                        @endif
                    </a>

                    <div style="min-width:0;">
                        <p style="color:var(--muted);font-size:0.78rem;margin:0 0 0.35rem;text-transform:uppercase;letter-spacing:0.08em;">{{ $item->product->brand?->name ?? 'Brand' }} • {{ ucfirst($item->product->category) }}</p>
                        <a href="{{ route('products.show', $item->product) }}" style="color:#fff;text-decoration:none;font-size:1.15rem;font-weight:600;line-height:1.2;">{{ $item->product->name }}</a>
                        @if($item->size)
                            <p style="color:#aaa;font-size:0.85rem;margin:0.35rem 0 0;">Size: <span style="color:#fff;font-weight:600;">{{ $item->size->name }}</span></p>
                        @endif
                        <p style="color:var(--accent);font-size:1.15rem;font-weight:700;margin:0.5rem 0 0.35rem;">₱{{ number_format($item->price, 2) }}</p>
                        <p style="color:{{ $item->product->stock > 0 ? '#4ADE80' : '#FF6B6B' }};font-size:0.8rem;margin:0;">
                            {{ $item->product->stock > 0 ? $item->product->stock . ' available' : 'Out of stock' }}
                        </p>
                    </div>

                    <div class="cart-item-actions" style="display:grid;gap:0.75rem;justify-items:end;min-width:170px;">
                        <form method="POST" action="{{ route('cart.update', $item->product) }}" style="display:flex;align-items:flex-end;gap:0.5rem;flex-wrap:wrap;justify-content:flex-end;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <div style="min-width:88px;">
                                <label for="qty-{{ $item->id }}" style="margin-bottom:0.35rem;color:var(--muted);font-size:0.72rem;display:block;">Qty</label>
                                <input id="qty-{{ $item->id }}" type="number" name="quantity" min="1" max="{{ max($item->product->stock, 1) }}" value="{{ $item->quantity }}" style="width:88px;">
                            </div>
                            <button type="submit" class="btn-outline" style="background:none;cursor:pointer;">Update</button>
                        </form>

                        <form method="POST" action="{{ route('cart.destroy', $item->product) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <button type="submit" class="btn-outline" style="background:none;cursor:pointer;color:#FF6B6B;border-color:#FF6B6B44;">Remove</button>
                        </form>

                        <div style="text-align:right;width:100%;padding-top:0.35rem;border-top:1px solid var(--border);">
                            <p style="color:var(--muted);font-size:0.72rem;margin:0 0 0.25rem;">Subtotal</p>
                            <p style="color:#fff;font-size:1.15rem;font-weight:700;margin:0;">₱{{ number_format($item->subtotal(), 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <aside class="card cart-summary" style="padding:1.5rem;border-radius:20px;position:sticky;top:88px;">
                <p style="color:var(--accent);font-size:0.75rem;letter-spacing:0.18em;font-weight:700;margin:0 0 0.75rem;text-transform:uppercase;">Checkout Summary</p>
                <h2 style="color:#fff;font-size:1.4rem;margin:0 0 1rem;line-height:1;">Ready to buy</h2>

                <form method="POST" action="{{ route('cart.checkout') }}" style="display:grid;gap:1rem;">
                    @csrf

                    <div style="padding:0.9rem 1rem;border-radius:14px;border:1px solid var(--border);background:#121212;display:grid;gap:0.75rem;">
                        <p style="margin:0;color:#fff;font-size:0.88rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Shipping Address</p>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" placeholder="Full name">
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number">
                        <input type="text" name="address_line1" value="{{ old('address_line1') }}" placeholder="Street address">
                        <input type="text" name="address_line2" value="{{ old('address_line2') }}" placeholder="Apartment, suite, unit, building (optional)">
                        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.75rem;">
                            <input type="text" name="city" value="{{ old('city') }}" placeholder="City">
                            <input type="text" name="province" value="{{ old('province') }}" placeholder="Province">
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0.75rem;">
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="Postal code">
                            <input type="text" name="country" value="{{ old('country', 'Philippines') }}" placeholder="Country">
                        </div>
                    </div>

                    <div style="padding:0.9rem 1rem;border-radius:14px;border:1px solid var(--border);background:#121212;">
                        <p style="margin:0 0 0.75rem;color:#fff;font-size:0.88rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Delivery</p>
                        <label style="display:flex;align-items:flex-start;gap:0.65rem;margin-bottom:0.65rem;cursor:pointer;padding:0.7rem;border:1px solid #2f2f2f;border-radius:10px;">
                            <input type="radio" name="delivery_window" value="standard" {{ old('delivery_window', 'standard') === 'standard' ? 'checked' : '' }} style="margin-top:0.1rem;">
                            <span>
                                <span style="display:block;color:#fff;font-size:0.88rem;">Standard Delivery</span>
                                <span style="display:block;color:#8f8f8f;font-size:0.76rem;">3-5 business days • Free</span>
                            </span>
                        </label>
                        <label style="display:flex;align-items:flex-start;gap:0.65rem;cursor:pointer;padding:0.7rem;border:1px solid #2f2f2f;border-radius:10px;">
                            <input type="radio" name="delivery_window" value="express" {{ old('delivery_window') === 'express' ? 'checked' : '' }} style="margin-top:0.1rem;">
                            <span>
                                <span style="display:block;color:#fff;font-size:0.88rem;">Express Delivery</span>
                                <span style="display:block;color:#8f8f8f;font-size:0.76rem;">1-2 business days • ₱249</span>
                            </span>
                        </label>
                    </div>

                    <div style="padding:0.9rem 1rem;border-radius:14px;border:1px solid var(--border);background:#121212;">
                        <p style="margin:0 0 0.75rem;color:#fff;font-size:0.88rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">Payment Method</p>
                        <label style="display:flex;align-items:flex-start;gap:0.65rem;margin-bottom:0.65rem;cursor:pointer;padding:0.7rem;border:1px solid #2f2f2f;border-radius:10px;">
                            <input type="radio" name="payment_method" value="card" {{ old('payment_method', 'card') === 'card' ? 'checked' : '' }} style="margin-top:0.1rem;">
                            <span>
                                <span style="display:block;color:#fff;font-size:0.88rem;">Credit or Debit Card</span>
                                <span style="display:block;color:#8f8f8f;font-size:0.76rem;">Visa, Mastercard, JCB</span>
                            </span>
                        </label>
                        <label style="display:flex;align-items:flex-start;gap:0.65rem;margin-bottom:0.65rem;cursor:pointer;padding:0.7rem;border:1px solid #2f2f2f;border-radius:10px;">
                            <input type="radio" name="payment_method" value="gcash" {{ old('payment_method') === 'gcash' ? 'checked' : '' }} style="margin-top:0.1rem;">
                            <span>
                                <span style="display:block;color:#fff;font-size:0.88rem;">GCash</span>
                                <span style="display:block;color:#8f8f8f;font-size:0.76rem;">Fast wallet checkout</span>
                            </span>
                        </label>
                        <label style="display:flex;align-items:flex-start;gap:0.65rem;cursor:pointer;padding:0.7rem;border:1px solid #2f2f2f;border-radius:10px;">
                            <input type="radio" name="payment_method" value="cash_on_delivery" {{ old('payment_method') === 'cash_on_delivery' ? 'checked' : '' }} style="margin-top:0.1rem;">
                            <span>
                                <span style="display:block;color:#fff;font-size:0.88rem;">Cash on Delivery</span>
                                <span style="display:block;color:#8f8f8f;font-size:0.76rem;">Pay when your order arrives</span>
                            </span>
                        </label>
                    </div>

                    <div style="display:grid;gap:0.85rem;margin-bottom:0.2rem;">
                        <div style="display:flex;justify-content:space-between;color:#aaa;">
                            <span>Items</span>
                            <span>{{ $cart->items->sum('quantity') }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;color:#aaa;">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;color:#aaa;">
                            <span>Shipping</span>
                            <span id="shippingPreview">₱0.00</span>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:space-between;color:#fff;font-size:1.25rem;font-weight:700;padding-top:1rem;border-top:1px solid var(--border);margin-bottom:0.15rem;">
                        <span>Total</span>
                        <span id="totalPreview">₱{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <button type="submit" class="btn-accent" style="width:100%;border:none;cursor:pointer;font-size:1rem;padding:0.95rem 1rem;">Secure Checkout</button>
                </form>

                <div style="margin-top:1rem;padding:0.9rem 1rem;border-radius:14px;background:rgba(200,255,0,0.06);border:1px solid rgba(200,255,0,0.14);color:#dfe8c0;font-size:0.82rem;line-height:1.65;">
                    Checkout validates stock, decrements inventory, and sends you to a full order confirmation screen.
                </div>
            </aside>
        </div>
    @endif
</div>

<script>
(() => {
    const subtotal = {{ json_encode($subtotal) }};
    const expressFee = {{ json_encode($expressFee) }};
    const shippingNode = document.getElementById('shippingPreview');
    const totalNode = document.getElementById('totalPreview');
    const deliveryInputs = document.querySelectorAll('input[name="delivery_window"]');

    const currency = (amount) => `₱${amount.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

    const refreshTotals = () => {
        const selected = document.querySelector('input[name="delivery_window"]:checked')?.value || 'standard';
        const shipping = selected === 'express' ? expressFee : 0;
        shippingNode.textContent = currency(shipping);
        totalNode.textContent = currency(subtotal + shipping);
    };

    deliveryInputs.forEach((input) => input.addEventListener('change', refreshTotals));
    refreshTotals();
})();
</script>
@endsection