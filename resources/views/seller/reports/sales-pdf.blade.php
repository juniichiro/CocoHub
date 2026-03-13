<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        .header { text-align: center; border-bottom: 2px solid #738D56; padding-bottom: 10px; margin-bottom: 20px; }
        .brand-coco { color: #6D4C41; font-weight: bold; font-size: 24px; }
        .brand-hub { color: #738D56; font-weight: bold; font-size: 24px; }
        
        /* TABLE-BASED STATS ROW: Bulletproof single-row layout for PDF */
        .stats-table { 
            width: 100%; 
            margin-bottom: 25px; 
            border-collapse: separate; 
            border-spacing: 8px 0; 
            margin-left: -8px; 
        }
        
        .stat-card { 
            background: #F9F7F2; 
            padding: 15px 5px; 
            border-radius: 12px; 
            text-align: center; 
            width: 25%; 
        }
        
        .stat-label { font-size: 8px; color: #888; text-transform: uppercase; font-weight: bold; margin-bottom: 8px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #222; }

        /* Transactions Table */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th { background: #738D56; color: white; padding: 12px 10px; font-size: 10px; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
        
        td { padding: 12px 10px; border-bottom: 1px solid #f0f0f0; font-size: 9px; vertical-align: middle; }
        
        .text-right { text-align: right; }
        .customer-name { font-weight: bold; color: #202124; font-size: 10px; }
        .order-id { color: #6D4C41; font-weight: bold; }

        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 12px; margin-top: 5px;">Performance & Sales Report</div>
        <div style="font-size: 9px; color: #666;">Generated on: {{ $generatedAt }}</div>
    </div>

    {{-- Single Row Metrics using Table --}}
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
                <div class="stat-value" style="color: #738D56;">{{ $completedOrdersCount }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Pending Revenue</div>
                <div class="stat-value" style="color: #ca8a04;">&#8369;{{ number_format($pendingRevenue, 2) }}</div>
            </td>
        </tr>
    </table>

    <h3 style="color: #6D4C41; font-size: 14px; margin-left: 5px; margin-bottom: 10px;">Recent Completed Transactions</h3>
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
                <td class="text-right" style="font-weight: bold;">&#8369;{{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        CocoHub Marketplace - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>