<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Display the buyer's personal order history.
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
                      ->with(['items.product', 'review']); 

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('id', 'LIKE', '%' . $request->search . '%');
        }

        $orders = $query->latest()->get();

        $orders->each(function ($order) {
            $order->is_rated = $order->review !== null;
        });

        return view('buyer.history', compact('orders'));
    }

    /**
     * Store the buyer's review and update individual product ratings.
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'nullable|string|max:500',
        ]);

        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->where('status', 'Completed')
                      ->with('items.product') 
                      ->firstOrFail();

        if ($order->review()->exists()) {
            return back()->with('error', 'You have already rated this order.');
        }

        DB::transaction(function () use ($request, $order) {
            
            // 1. Loop through each item in the order to create a product-specific review record
            // This ensures that the Seller's Review page can see the product name
            foreach ($order->items as $item) {
                $product = $item->product;

                // Create the review linked to BOTH the order and the specific product
                Review::create([
                    'user_id'    => Auth::id(),
                    'order_id'   => $order->id,
                    'product_id' => $product->id, // CRITICAL: This fixes your null error
                    'rating'     => $request->rating,
                    'comment'    => $request->comment,
                ]);

                // 2. Update the rolling average for the product
                $oldCount = $product->review_count;
                $oldRating = $product->rating;
                $newCount = $oldCount + 1;
                
                // Rolling Average Formula: ((CurrentAvg * CurrentCount) + NewRating) / NewCount
                $newRating = (($oldRating * $oldCount) + $request->rating) / $newCount;

                $product->update([
                    'rating'       => $newRating,
                    'review_count' => $newCount
                ]);
            }
        });

        return back()->with('status', 'Thank you for your feedback! Your rating helps other buyers.');
    }
}