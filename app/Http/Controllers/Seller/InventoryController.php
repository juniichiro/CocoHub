<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str; // Added for slug generation

class InventoryController extends Controller
{
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
            
            $safeName = str_replace('-', '_', Str::slug($request->name));
            $filename = $safeName . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        Product::create($validated);

        return back()->with('status', 'product-added');
    }

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
            if ($product->image) {
                $oldPath = public_path('images/products/' . $product->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            $file = $request->file('image');
            
            $safeName = str_replace('-', '_', Str::slug($request->name));
            $filename = $safeName . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images/products'), $filename);
            $validated['image'] = $filename;
        }

        $product->update($validated);

        return back()->with('status', 'product-updated');
    }

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
    public function exportPDF()
    {
        $products = Product::all();
        $now = Carbon::now('Asia/Manila');

        $data = [
            'products' => $products,
            'generatedAt' => $now->format('F d, Y h:i A'),
            'stats' => [
                'total' => $products->count(),
                'inStock' => $products->where('stock', '>', 10)->count(),
                'lowStock' => $products->where('stock', '<=', 10)->where('stock', '>', 0)->count(),
                'outOfStock' => $products->where('stock', '<=', 0)->count(),
            ]
        ];

        $pdf = Pdf::loadView('seller.reports.inventory-pdf', $data);
        
        return $pdf->setPaper('a4', 'portrait')
                ->download('CocoHub-Inventory-Report-' . $now->format('Y-m-d') . '.pdf');
    }
}