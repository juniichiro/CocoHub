<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . trim($request->search) . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->when($request->filled('availability'), function ($query) use ($request) {
                if ($request->availability === 'In Stock') {
                    $query->where('stock', '>', 0);
                } elseif ($request->availability === 'Out of Stock') {
                    $query->where('stock', '<=', 0);
                }
            })
            ->when($request->filled('sort'), function ($query) use ($request) {
                if ($request->sort === 'Price: Low to High') {
                    $query->orderBy('price', 'asc');
                } elseif ($request->sort === 'Price: High to Low') {
                    $query->orderBy('price', 'desc');
                } else {
                    $query->latest('id');
                }
            }, function ($query) {
                $query->latest('id');
            })
            ->get();

        return view('buyer.product', compact('products'));
    }


    public function show(Product $product)
    {
        return view('buyer.product-detail', compact('product'));
    }
}