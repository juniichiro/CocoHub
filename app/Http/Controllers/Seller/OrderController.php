<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('id', $search)
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%$search%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();
        return view('seller.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Awaiting Shipping,On Delivery,Completed,Cancelled'
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('status', 'Order status updated successfully.');
    }
}
