<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StorefrontSetting; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Manila');
        $now = Carbon::now('Asia/Manila');

        $salesToday = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        $salesThisMonth = Order::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', '!=', 'Cancelled')
            ->sum('total_amount');

        $totalOrders = Order::count();
        $newOrdersToday = Order::whereDate('created_at', $today)->count();

        $lowStockCount = Product::where('stock', '<', 10)->count();

        $products = Product::latest()->take(5)->get();

        $recentOrders = Order::with(['items.product'])
            ->latest()
            ->take(4)
            ->get();

        
        $settings = StorefrontSetting::first(); 

        return view('seller.dashboard', compact(
            'salesToday', 
            'salesThisMonth', 
            'totalOrders', 
            'newOrdersToday', 
            'lowStockCount', 
            'products', 
            'recentOrders',
            'settings' 
        ));
    }
}