<?php
namespace App\Http\Controllers;

use App\Models\Order;

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
}
