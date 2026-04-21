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

        $cart = $this->getCart()->load(['items.product.brand', 'items.size']);

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
            'size_id' => ['nullable', 'integer', 'exists:sizes,id'],
        ]);

        // If product has sizes, size_id is required
        $sizeId = $validated['size_id'] ?? null;

        if ($product->sizes->count() > 0 && ! $sizeId) {
            return redirect()->route('products.show', $product)->with('error', 'Please select a size.');
        }

        if ($sizeId) {
            $selectedSize = $product->sizes()->where('sizes.id', $sizeId)->first();
            if (! $selectedSize) {
                return redirect()->route('products.show', $product)->with('error', 'Invalid size selected for this product.');
            }
        }

        $quantity = $validated['quantity'] ?? 1;
        $cart = $this->getCart();
        
        // Check for existing item with same product and size
        $query = $cart->items()->where('product_id', $product->id);
        if ($sizeId) {
            $query->where('size_id', $sizeId);
        }
        $item = $query->first();
        
        $newQuantity = $item ? $item->quantity + $quantity : $quantity;

        if ($sizeId) {
            $availableSizeStock = (int) ($selectedSize->pivot->stock ?? 0);
            if ($newQuantity > $availableSizeStock) {
                return redirect()->route('products.show', $product)->with('error', 'Requested quantity exceeds available stock for that size.');
            }
        }

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
                'size_id' => $sizeId,
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

        $cart = $this->getCart()->load('items.product.brand', 'items.size');

        $validated = request()->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'province' => ['required', 'string', 'max:120'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:120'],
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

            if ($item->size_id) {
                $size = $item->product->sizes()->where('sizes.id', $item->size_id)->first();
                $sizeStock = (int) ($size?->pivot?->stock ?? 0);
                if (! $size || $item->quantity > $sizeStock) {
                    return redirect()->route('cart.index')->with('error', 'Some selected sizes are out of stock. Please update your cart.');
                }
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
            'status' => 'pending',
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'],
            'province' => $validated['province'],
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
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

                if ($item->size_id) {
                    $size = $product->sizes()->where('sizes.id', $item->size_id)->first();
                    $sizeStock = (int) ($size?->pivot?->stock ?? 0);

                    if (! $size || $item->quantity > $sizeStock) {
                        abort(409, 'Checkout failed due to size stock changes.');
                    }

                    DB::table('product_size')
                        ->where('product_id', $product->id)
                        ->where('size_id', $item->size_id)
                        ->decrement('stock', $item->quantity);
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
                    'size_id' => $item->size_id,
                ]);
            }

            $cart->items()->delete();
            return $order->load('items');
        });

        $confirmationData = [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status_label,
            'placed_at' => $order->placed_at->format('M d, Y h:i A'),
            'estimated_arrival' => optional($order->estimated_arrival)->format('M d, Y'),
            'delivery_window' => $deliveryLabels[$order->delivery_window],
            'payment_method' => $paymentLabels[$order->payment_method],
            'recipient_name' => $order->recipient_name,
            'phone' => $order->phone,
            'shipping_address' => trim($order->address_line1 . ($order->address_line2 ? ', ' . $order->address_line2 : '') . ', ' . $order->city . ', ' . $order->province . ' ' . $order->postal_code . ', ' . $order->country),
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