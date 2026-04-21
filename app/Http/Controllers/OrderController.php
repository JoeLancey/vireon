<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller {
    protected function ensureCustomerAccess(): void {
        abort_if(auth()->user()->isAdmin(), 403);
    }

    public function index() {
        $this->ensureCustomerAccess();

        $orders = Order::query()
            ->where('user_id', auth()->id())
            ->latest('id')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order) {
        $this->ensureCustomerAccess();
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load('items');

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->ensureCustomerAccess();
        abort_if($order->user_id !== auth()->id(), 403);

        if (! $order->canBeCancelled()) {
            return back()->with('error', 'This order can no longer be cancelled.');
        }

        DB::transaction(function () use ($order) {
            $order->loadMissing('items.product');

            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', (int) $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'shipped_at' => null,
                'delivered_at' => null,
            ]);
        });

        return back()->with('success', 'Your order has been cancelled and inventory restored.');
    }
}
