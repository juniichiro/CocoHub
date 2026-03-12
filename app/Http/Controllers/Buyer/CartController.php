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
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        
        // Get quantity from request or default to 1
        $quantityToAdd = $request->input('quantity', 1);

        if ($product->stock < $quantityToAdd) {
            return back()->with('error', 'Sorry, only ' . $product->stock . ' units are available.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantityToAdd;
            
            if ($newQuantity <= $product->stock) {
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                return back()->with('error', 'Adding this would exceed available stock.');
            }
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantityToAdd
            ]);
        }

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