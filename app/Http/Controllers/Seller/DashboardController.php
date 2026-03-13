<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StorefrontSetting; // Assuming this is your model name
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set timezone to PH for consistency across the dashboard
        $today = Carbon::today('Asia/Manila');
        $now = Carbon::now('Asia/Manila');

        // 1. Sales Today (Using Manila Time)
        $salesToday = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        // 2. Sales This Month
        $salesThisMonth = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        // 3. Total Orders
        $totalOrders = Order::count();
        $newOrdersToday = Order::whereDate('created_at', $today)->count();

        // 4. Low Stock Items
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // 5. Inventory Overview
        $products = Product::latest()->take(5)->get();

        // 6. Recent Orders
        $recentOrders = Order::with(['items.product'])
            ->latest()
            ->take(4)
            ->get();

        // 7. Fetch Storefront Settings for the Hero Image
        // Adjust this line based on how you store your settings (e.g., first row)
        $settings = StorefrontSetting::first(); 

        return view('seller.dashboard', compact(
            'salesToday', 
            'salesThisMonth', 
            'totalOrders', 
            'newOrdersToday', 
            'lowStockCount', 
            'products', 
            'recentOrders',
            'settings' // Passing the settings to the view
        ));
    }
}