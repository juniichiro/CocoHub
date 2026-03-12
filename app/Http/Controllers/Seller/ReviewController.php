<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of customer reviews.
     */
    public function index()
    {
        // Eager load 'user' and 'product' to prevent N+1 performance issues
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->paginate(10);

        return view('seller.reviews', compact('reviews'));
    }
}