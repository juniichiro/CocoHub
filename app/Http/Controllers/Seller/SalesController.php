<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today('Asia/Manila');
        $now = Carbon::now('Asia/Manila');

        $totalSalesToday = Order::where('status', 'Completed')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $totalSalesMonth = Order::where('status', 'Completed')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_amount');

        $completedOrdersCount = Order::where('status', 'Completed')->count();

        $pendingRevenue = Order::whereIn('status', ['Awaiting Shipping', 'On Delivery'])
            ->sum('total_amount');

        $query = Order::where('status', 'Completed')
            ->with(['user.buyerDetail']);

        if ($request->filled('search')) {
            $query->where('id', 'LIKE', '%' . $request->search . '%');
        }

        $recentSales = $query->latest()->take(10)->get();

        $rawHourlyData = Order::where('status', 'Completed')
            ->whereDate('created_at', $today)
            ->select(
                DB::raw('HOUR(CONVERT_TZ(created_at, "+00:00", "+08:00")) as hour'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        $hourlySales = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlySales[] = [
                'hour' => $i,
                'total' => $rawHourlyData[$i] ?? 0
            ];
        }

        $monthlySales = Order::where('status', 'Completed')
            ->select(
                DB::raw('SUM(total_amount) as total'),
                DB::raw('MONTHNAME(created_at) as month'),
                DB::raw('MONTH(created_at) as month_num')
            )
            ->where('created_at', '>=', $now->copy()->subMonths(6)->startOfMonth())
            ->groupBy('month', 'month_num')
            ->orderBy('month_num')
            ->get();

        return view('seller.sales', [
            'totalSalesToday' => $totalSalesToday,
            'totalSalesMonth' => $totalSalesMonth,
            'completedOrdersCount' => $completedOrdersCount,
            'pendingRevenue' => $pendingRevenue,
            'recentSales' => $recentSales,
            'hourlySales' => $hourlySales, 
            'monthlySales' => $monthlySales
        ]);
    }

    public function exportPDF()
    {
        $today = Carbon::today('Asia/Manila');
        $now = Carbon::now('Asia/Manila');

        $data = [
            'totalSalesToday' => Order::where('status', 'Completed')->whereDate('created_at', $today)->sum('total_amount'),
            'totalSalesMonth' => Order::where('status', 'Completed')->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum('total_amount'),
            'completedOrdersCount' => Order::where('status', 'Completed')->count(),
            'pendingRevenue' => Order::whereIn('status', ['Awaiting Shipping', 'On Delivery'])->sum('total_amount'),
            
            'recentSales' => Order::where('status', 'Completed')
                ->with(['user.buyerDetail']) 
                ->latest()
                ->take(20)
                ->get(),
                
            'generatedAt' => $now->format('F d, Y h:i A')
        ];

        $pdf = Pdf::loadView('seller.reports.sales-pdf', $data);
        
        return $pdf->setPaper('a4', 'portrait')
                ->download('CocoHub-Sales-Report-' . $now->format('Y-m-d') . '.pdf');
    }
}