<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request)
    {
        $products = Product::query()
            // 1. Search Filter (handles whitespace and nulls)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->search) . '%');
            })
            // 2. Category Filter (exact match)
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            // 3. Availability Filter
            ->when($request->filled('availability'), function ($query) use ($request) {
                if ($request->availability === 'In Stock') {
                    $query->where('stock', '>', 0);
                } elseif ($request->availability === 'Out of Stock') {
                    $query->where('stock', '<=', 0);
                }
            })
            // 4. Sorting Logic
            ->when($request->filled('sort'), function ($query) use ($request) {
                if ($request->sort === 'Price: Low to High') {
                    $query->orderBy('price', 'asc');
                } elseif ($request->sort === 'Price: High to Low') {
                    $query->orderBy('price', 'desc');
                } else {
                    $query->latest('id');
                }
            }, function ($query) {
                // Default sorting if no sort is provided
                $query->latest('id');
            })
            ->get();

        return view('buyer.product', compact('products'));
    }

    /**
     * Display the specified product detail.
     */
    public function show(Product $product)
    {
        return view('buyer.product-detail', compact('product'));
    }
}