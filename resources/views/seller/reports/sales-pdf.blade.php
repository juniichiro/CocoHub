<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.5; margin: 0; padding: 0; font-size: 12px; }
        
        .header { text-align: center; border-bottom: 2px solid #738D56; padding-bottom: 15px; margin-bottom: 25px; }
        .brand-coco { color: #6D4C41; font-weight: bold; font-size: 28px; }
        .brand-hub { color: #738D56; font-weight: bold; font-size: 28px; }
        
        .stats-table { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px 0; margin-left: -10px; }
        
        .stat-card { background: #F9F7F2; padding: 20px 10px; border-radius: 15px; text-align: center; width: 25%; }
        
        .stat-label { font-size: 10px; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 10px; }
        .stat-value { font-size: 20px; font-weight: bold; color: #222; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        th { background: #738D56; color: white; padding: 14px 12px; font-size: 11px; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
        
        td { padding: 14px 12px; border-bottom: 1px solid #f0f0f0; font-size: 11px; vertical-align: middle; }
        
        .text-right { text-align: right; }
        .customer-name { font-weight: bold; color: #202124; font-size: 12px; }
        .order-id { color: #6D4C41; font-weight: bold; font-size: 11px; }

        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 14px; margin-top: 5px;">Performance & Sales Report</div>
        <div style="font-size: 11px; color: #666;">Generated on: {{ $generatedAt }}</div>
    </div>

    <table class="stats-table">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Sales Today</div>
                <div class="stat-value">&#8369;{{ number_format($totalSalesToday, 2) }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">This Month</div>
                <div class="stat-value">&#8369;{{ number_format($totalSalesMonth, 2) }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Completed</div>
                <div class="stat-value">{{ $completedOrdersCount }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Pending Revenue</div>
                <div class="stat-value">&#8369;{{ number_format($pendingRevenue, 2) }}</div>
            </td>
        </tr>
    </table>

    <h3 style="color: #6D4C41; font-size: 16px; margin-left: 5px; margin-bottom: 12px;">Recent Completed Transactions</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%">Order ID</th>
                <th style="width: 35%">Customer Name</th>
                <th style="width: 25%">Date (PHT)</th>
                <th style="width: 20%" class="text-right">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentSales as $order)
            <tr>
                <td class="order-id">#{{ $order->id }}</td>
                <td class="customer-name">
                    @if($order->user && $order->user->buyerDetail)
                        {{ $order->user->buyerDetail->first_name }} {{ $order->user->buyerDetail->last_name }}
                    @else
                        {{ $order->user->name ?? 'Guest Customer' }}
                    @endif
                </td>
                <td style="color: #666;">{{ $order->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                <td class="text-right" style="font-weight: bold; font-size: 12px;">&#8369;{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        CocoHub - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>