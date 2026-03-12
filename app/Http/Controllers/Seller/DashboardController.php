<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Sales Today
        $salesToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        // 2. Sales This Month
        $salesThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        // 3. Total Orders (Overall)
        $totalOrders = Order::count();
        $newOrdersToday = Order::whereDate('created_at', Carbon::today())->count();

        // 4. Low Stock Items (Stock < 10)
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // 5. Inventory Overview (Limit to 5 for dashboard)
        $products = Product::latest()->take(5)->get();

        // 6. Recent Orders (With items and products)
        $recentOrders = Order::with(['items.product'])
            ->latest()
            ->take(4)
            ->get();

        return view('seller.dashboard', compact(
            'salesToday', 
            'salesThisMonth', 
            'totalOrders', 
            'newOrdersToday', 
            'lowStockCount', 
            'products', 
            'recentOrders'
        ));
    }
}