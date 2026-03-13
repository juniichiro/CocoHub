<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.5; }
        
        .header { text-align: center; border-bottom: 2px solid #738D56; padding-bottom: 10px; margin-bottom: 20px; }
        .brand-coco { color: #6D4C41; font-weight: bold; font-size: 24px; }
        .brand-hub { color: #738D56; font-weight: bold; font-size: 24px; }
        
        .metrics { width: 100%; margin-bottom: 30px; }
        .metric-box { background: #F9F7F2; padding: 15px; border-radius: 10px; width: 22%; display: inline-block; text-align: center; margin-right: 1%; }
        
        .label { font-size: 9px; color: #888; text-transform: uppercase; font-weight: bold; }
        .value { font-size: 14px; font-weight: bold; color: #222; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #738D56; color: white; padding: 10px; font-size: 11px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        
        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 12px; margin-top: 5px;">Performance & Sales Report</div>
        <div style="font-size: 10px; color: #666;">Generated on: {{ $generatedAt }}</div>
    </div>

    <div class="metrics">
        <div class="metric-box">
            <div class="label">Sales Today</div>
            <div class="value">&#8369;{{ number_format($totalSalesToday, 2) }}</div>
        </div>
        <div class="metric-box">
            <div class="label">This Month</div>
            <div class="value">&#8369;{{ number_format($totalSalesMonth, 2) }}</div>
        </div>
        <div class="metric-box">
            <div class="label">Completed</div>
            <div class="value">{{ $completedOrdersCount }}</div>
        </div>
        <div class="metric-box">
            <div class="label">Pending</div>
            <div class="value">&#8369;{{ number_format($pendingRevenue, 2) }}</div>
        </div>
    </div>

    <h3 style="color: #6D4C41; font-size: 14px; margin-left: 5px;">Recent Completed Transactions</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Name</th>
                <th>Date (PHT)</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentSales as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>
                    @if($order->user && $order->user->buyerDetail)
                        {{ $order->user->buyerDetail->first_name }} {{ $order->user->buyerDetail->last_name }}
                    @else
                        {{ $order->user->name ?? 'Guest Customer' }}
                    @endif
                </td>
                <td>{{ $order->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                <td>&#8369;{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        CocoHub Marketplace - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>