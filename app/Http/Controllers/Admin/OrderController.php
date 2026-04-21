<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('user')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($builder) use ($search) {
                $builder->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('recipient_name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', fn ($userQuery) => $userQuery->where('email', 'like', '%' . $search . '%'));
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,shipped,delivered,cancelled'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        if ($order->status === 'cancelled' && $validated['status'] !== 'cancelled') {
            return back()->with('error', 'Cancelled orders cannot be reactivated.');
        }

        $updates = [
            'status' => $validated['status'],
            'tracking_number' => $validated['tracking_number'] ?? null,
        ];

        if ($validated['status'] === 'shipped' && ! $order->shipped_at) {
            $updates['shipped_at'] = now();
        }

        if ($validated['status'] === 'delivered' && ! $order->delivered_at) {
            $updates['delivered_at'] = now();
        }

        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            $order->loadMissing('items.product');

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', (int) $item->quantity);
                }
            }

            $updates['cancelled_at'] = now();
            $updates['shipped_at'] = null;
            $updates['delivered_at'] = null;
        }

        if ($validated['status'] !== 'delivered' && $order->delivered_at) {
            $updates['delivered_at'] = null;
        }

        if ($validated['status'] !== 'shipped' && $order->shipped_at && $validated['status'] !== 'delivered') {
            $updates['shipped_at'] = null;
        }

        $order->update($updates);

        return back()->with('success', 'Order updated successfully.');
    }
}