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
    /**
     * Display the buyer's shopping cart.
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with(['items.product'])
            ->first();

        return view('buyer.cart', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        // 1. Safety Check: Is it in stock?
        if ($product->stock <= 0) {
            return back()->with('error', 'Sorry, this item is out of stock.');
        }

        // 2. Find or Create the User's Cart
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // 3. Check if the item already exists in the cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Increase quantity if it exists
            $cartItem->increment('quantity');
        } else {
            // Create a new item if it doesn't
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        // 4. Return with the Success status you already check for in your Blade
        return back()->with('status', 'added-to-cart');
    }

    /**
     * Remove an item from the cart safely.
     */
    public function remove(Request $request, $itemId)
    {
        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        // Check if we should decrement or remove entirely
        if ($request->has('decrement') && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
            $status = 'item-updated';
        } else {
            $cartItem->delete();
            $status = 'item-removed';
        }

        return back()->with('status', $status);
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if ($cart) {
            $cart->items()->delete();
        }

        return back()->with('status', 'cart-cleared');
    }
}