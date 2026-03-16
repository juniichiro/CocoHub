@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]">
    <x-seller-sidebar />

    <main class="flex-grow ml-64 p-12 flex flex-col">
        <div class="flex-grow">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Seller Performance</p>
                    <h2 class="text-3xl font-bold text-gray-900">Sales Report</h2>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('seller.sales.export') }}" class="px-6 py-3 bg-[#738D56] text-white rounded-2xl text-sm font-bold hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20">
                        Export Report
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-6 mb-12">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales Today</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">₱{{ number_format($totalSalesToday ?? 0, 2) }}</h4>
                    <p class="text-[10px] font-bold text-[#738D56] uppercase tracking-tight">▲ Real-time update</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales This Month</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">₱{{ number_format($totalSalesMonth ?? 0, 2) }}</h4>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">Steady monthly growth</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Completed Orders</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">{{ $completedOrdersCount }}</h4>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">Total successful deliveries</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Pending Revenue</p>
                    <h4 class="text-3xl font-black text-amber-500 mb-2">₱{{ number_format($pendingRevenue ?? 0, 2) }}</h4>
                    <p class="text-[10px] font-bold text-amber-300 uppercase tracking-tight">In transit / Awaiting</p>
                </div>
            </div>

            <div class="flex justify-between items-end mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Revenue Analysis</h2>
                <a href="{{ route('seller.inventory') }}" class="px-8 py-3 bg-[#738D56] text-white font-bold rounded-xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition-all">Manage Inventory</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-[2.5rem] p-10 border border-gray-50 shadow-sm">
                    <div class="mb-8">
                        <h3 class="font-bold text-gray-800 italic">Sold Today</h3>
                        <p class="text-xs text-gray-400">Hourly revenue distribution (Asia/Manila).</p>
                    </div>
                    
                    <div class="h-64 mb-8">
                        <canvas id="todaySalesChart"></canvas>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-4xl font-black text-gray-900">₱{{ number_format($totalSalesToday ?? 0, 2) }}</h4>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 pb-4">Total sales recorded for the day</p>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 border border-gray-50 shadow-sm">
                    <div class="mb-8">
                        <h3 class="font-bold text-gray-800 italic">Monthly Performance</h3>
                        <p class="text-xs text-gray-400">Revenue distribution (Last 6 Months).</p>
                    </div>

                    <div class="h-64 mb-8">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-4xl font-black text-gray-900">₱{{ number_format($totalSalesMonth ?? 0, 2) }}</h4>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 pb-4">Total sales for the current month</p>
                    </div>
                </div>
            </div>
        </div>

        <x-seller-footer />
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const primaryColor = '#738D56';
    const secondaryColor = 'rgba(115, 141, 86, 0.1)';

    const currencyFormatter = (value) => '₱' + new Intl.NumberFormat('en-PH').format(value);

    const todayLabels = {!! json_encode(array_map(function($h) {
        $hour = $h['hour'];
        return $hour == 0 ? '12 AM' : ($hour > 12 ? ($hour - 12) . ' PM' : ($hour == 12 ? '12 PM' : $hour . ' AM'));
    }, $hourlySales)) !!};
    
    const todayData = {!! json_encode(array_column($hourlySales, 'total')) !!};

    const todayCtx = document.getElementById('todaySalesChart').getContext('2d');
    new Chart(todayCtx, {
        type: 'line',
        data: {
            labels: todayLabels,
            datasets: [{
                label: 'Hourly Sales',
                data: todayData,
                borderColor: primaryColor,
                backgroundColor: secondaryColor,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: primaryColor
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: { label: (context) => ` Revenue: ${currencyFormatter(context.raw)}` }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 }, callback: (v) => '₱' + v } },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 0 } }
            }
        }
    });

    const monthLabels = {!! json_encode($monthlySales->pluck('month')) !!};
    const monthData = {!! json_encode($monthlySales->pluck('total')) !!};

    const monthCtx = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(monthCtx, {
        type: 'bar',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Monthly Revenue',
                data: monthData,
                backgroundColor: primaryColor,
                borderRadius: 8,
                barThickness: 25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    callbacks: { label: (context) => ` Total: ${currencyFormatter(context.raw)}` }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { font: { size: 10 }, callback: (v) => '₱' + v } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
</script>
@endsection