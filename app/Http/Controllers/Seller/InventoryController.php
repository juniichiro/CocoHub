<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str; // Added for slug generation

class InventoryController extends Controller
{
    /**
     * Display the global inventory.
     */
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('category', 'LIKE', "%$search%");
            });
        }

        $products = $query->latest('id')->get();

        return view('seller.inventory', compact('products'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Generate filename: "full_product_name.extension"
            $safeName = str_replace('-', '_', Str::slug($request->name));
            $filename = $safeName . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        Product::create($validated);

        return back()->with('status', 'product-added');
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image file if it exists
            if ($product->image) {
                $oldPath = public_path('images/products/' . $product->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('image');
            
            // Re-generate filename based on (potentially new) product name
            $safeName = str_replace('-', '_', Str::slug($request->name));
            $filename = $safeName . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        $product->update($validated);

        return back()->with('status', 'product-updated');
    }

    /**
     * Delete a product and its associated image.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image) {
            $imagePath = public_path('images/products/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return back()->with('status', 'product-deleted');
    }
}