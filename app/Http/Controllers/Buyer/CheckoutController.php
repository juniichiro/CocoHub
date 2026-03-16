<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('buyer.cart')->with('status', 'cart-empty');
        }

        return view('buyer.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|string',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();

        DB::transaction(function () use ($request, $cart) {
            $subtotal = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
            $total = $subtotal + 80; // Total + Delivery Fee

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'status' => 'Awaiting Shipping', 
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();
        });

        return redirect()->route('buyer.history')->with('status', 'Order placed successfully!');
    }
}