<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        // 1. Total Sales Today (Only Completed)
        $totalSalesToday = Order::where('status', 'Completed')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // 2. Sales This Month (Only Completed)
        $totalSalesMonth = Order::where('status', 'Completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // 3. Completed Order Count (Lifetime)
        $completedOrdersCount = Order::where('status', 'Completed')->count();

        // 4. Pending Revenue (Orders still in fulfillment)
        $pendingRevenue = Order::whereIn('status', ['Awaiting Shipping', 'On Delivery'])
            ->sum('total_amount');

        // 5. Recent Completed Transactions with Search Filter
        $query = Order::where('status', 'Completed')
            ->with(['user.buyerDetail']);

        if ($request->filled('search')) {
            $query->where('id', 'LIKE', '%' . $request->search . '%');
        }

        $recentSales = $query->latest()->take(10)->get();

        // 6. Data for Hourly Breakdown (Today)
        $hourlySales = Order::where('status', 'Completed')
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('HOUR(created_at) as hour, SUM(total_amount) as total')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // 7. Data for Monthly Performance Chart (Last 6 Months)
        // We need this for the bar chart in your view
        $monthlySales = Order::where('status', 'Completed')
            ->selectRaw('SUM(total_amount) as total, MONTHNAME(created_at) as month, MONTH(created_at) as month_num')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        return view('seller.sales', compact(
            'totalSalesToday',
            'totalSalesMonth',
            'completedOrdersCount',
            'pendingRevenue',
            'recentSales',
            'hourlySales',
            'monthlySales' // Make sure this is passed now
        ));
    }
}