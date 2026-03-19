<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with(['items' => function($query) {
                $query->with(['product:id,name,price,image,stock']); 
            }])
            ->first();

        return view('buyer.cart', compact('cart'));
    }

    public function add(Request $request, Product $product)
    {
        if ($product->stock <= 0) {
            return back()->with('error', 'Sorry, this item is out of stock.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            if ($cartItem->quantity < $product->stock) {
                $cartItem->increment('quantity');
            } else {
                return back()->with('error', 'Cannot add more than available stock.');
            }
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return back()->with('status', 'added-to-cart');
    }

    public function remove(Request $request, $itemId)
    {
        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        if ($request->has('decrement') && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $status = 'item-updated';
        } else {
            $cartItem->delete();
            $status = 'item-removed';
        }

        return back()->with('status', $status);
    }

    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('status', 'cart-cleared');
    }
}