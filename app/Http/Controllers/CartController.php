<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller {
    protected function ensureCustomerAccess(): void {
        abort_if(auth()->user()->isAdmin(), 403);
    }

    protected function getCart() {
        return Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            []
        );
    }

    protected function productIsArchived(Product $product): bool {
        return (bool) $product->is_archived;
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'VRN-' . now()->format('Ymd') . '-' . random_int(1000, 9999);
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    public function index() {
        $this->ensureCustomerAccess();

        $cart = $this->getCart()->load(['items.product.brand']);

        $cart->items = $cart->items
            ->filter(fn ($item) => $item->product && ! $item->product->is_archived)
            ->values();

        return view('cart.index', compact('cart'));
    }

    public function store(Request $request, Product $product) {
        $this->ensureCustomerAccess();

        if ($this->productIsArchived($product)) {
            return redirect()->route('products.index')->with('error', 'This product is no longer available.');
        }

        if ($product->stock < 1) {
            return redirect()->route('products.show', $product)->with('error', 'This product is out of stock.');
        }

        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $cart = $this->getCart();
        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = $item ? $item->quantity + $quantity : $quantity;

        if ($newQuantity > $product->stock) {
            return redirect()->route('products.show', $product)->with('error', 'Requested quantity exceeds available stock.');
        }

        if ($item) {
            $item->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Product $product) {
        $this->ensureCustomerAccess();

        $cart = $this->getCart();
        $item = $cart->items()->where('product_id', $product->id)->firstOrFail();

        if ($this->productIsArchived($product)) {
            $item->delete();

            return redirect()->route('cart.index')->with('error', 'That product is no longer available and was removed from your cart.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantity'] > $product->stock) {
            return redirect()->route('cart.index')->with('error', 'Requested quantity exceeds available stock.');
        }

        $item->update([
            'quantity' => $validated['quantity'],
            'price' => $product->price,
        ]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy(Product $product) {
        $this->ensureCustomerAccess();

        $cart = $this->getCart();
        $cart->items()->where('product_id', $product->id)->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear() {
        $this->ensureCustomerAccess();

        $cart = $this->getCart();
        $cart->items()->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    public function checkout() {
        $this->ensureCustomerAccess();

        $cart = $this->getCart()->load('items.product.brand');

        $validated = request()->validate([
            'delivery_window' => ['required', 'in:standard,express'],
            'payment_method' => ['required', 'in:card,cash_on_delivery,gcash'],
        ]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->items as $item) {
            if (! $item->product) {
                return redirect()->route('cart.index')->with('error', 'One or more items are no longer available.');
            }

            if ($this->productIsArchived($item->product)) {
                return redirect()->route('cart.index')->with('error', 'One or more items are no longer available.');
            }

            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', 'Some cart items exceed available stock. Please update your cart.');
            }
        }

        $deliveryDays = [
            'standard' => 5,
            'express' => 2,
        ];

        $subtotal = (float) $cart->items->sum(fn ($item) => $item->quantity * $item->price);
        $shippingFee = $validated['delivery_window'] === 'express' ? 249.00 : 0.00;
        $total = $subtotal + $shippingFee;
        $placedAt = now();
        $estimatedArrival = $placedAt->copy()->addDays($deliveryDays[$validated['delivery_window']]);
        $orderNumber = $this->generateOrderNumber();

        $deliveryLabels = [
            'standard' => 'Standard Delivery (3-5 business days)',
            'express' => 'Express Delivery (1-2 business days)',
        ];

        $paymentLabels = [
            'card' => 'Credit or Debit Card',
            'cash_on_delivery' => 'Cash on Delivery',
            'gcash' => 'GCash',
        ];

        $order = DB::transaction(function () use ($cart, $validated, $orderNumber, $placedAt, $estimatedArrival, $subtotal, $shippingFee, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => $orderNumber,
                'delivery_window' => $validated['delivery_window'],
                'payment_method' => $validated['payment_method'],
                'items_count' => (int) $cart->items->sum('quantity'),
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'placed_at' => $placedAt,
                'estimated_arrival' => $estimatedArrival,
            ]);

            foreach ($cart->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if (! $product || $this->productIsArchived($product) || $item->quantity > $product->stock) {
                    abort(409, 'Checkout failed due to stock changes.');
                }

                $product->decrement('stock', $item->quantity);

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'brand_name' => $item->product->brand?->name,
                    'category' => $item->product->category,
                    'quantity' => (int) $item->quantity,
                    'unit_price' => (float) $item->price,
                    'subtotal' => (float) ($item->quantity * $item->price),
                    'product_image' => $item->product->image,
                ]);
            }

            $cart->items()->delete();
            return $order->load('items');
        });

        $confirmationData = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'placed_at' => $order->placed_at->format('M d, Y h:i A'),
            'estimated_arrival' => optional($order->estimated_arrival)->format('M d, Y'),
            'delivery_window' => $deliveryLabels[$order->delivery_window],
            'payment_method' => $paymentLabels[$order->payment_method],
            'items_count' => (int) $order->items_count,
            'subtotal' => (float) $order->subtotal,
            'shipping_fee' => (float) $order->shipping_fee,
            'total' => (float) $order->total,
            'items' => $order->items->map(fn ($item) => [
                'name' => $item->product_name,
                'brand' => $item->brand_name ?? 'Brand',
                'quantity' => (int) $item->quantity,
                'price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
                'image' => $item->product_image,
            ])->values()->all(),
        ];

        session(['checkout_confirmation' => $confirmationData]);

        return redirect()->route('cart.confirmation')->with('success', 'Checkout completed successfully. Your order has been placed.');
    }

    public function confirmation()
    {
        $this->ensureCustomerAccess();

        $confirmation = session('checkout_confirmation');

        if (! $confirmation) {
            return redirect()->route('cart.index')->with('error', 'No recent checkout was found.');
        }

        return view('cart.confirmation', compact('confirmation'));
    }
}