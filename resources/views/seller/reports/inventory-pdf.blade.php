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
        .stat-value { font-size: 20px; font-weight: bold; color: #202124; }

        table.product-table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        th { background: #738D56; color: white; padding: 14px 12px; font-size: 11px; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
        th.status-header { text-align: center; }
        
        td { padding: 14px 12px; border-bottom: 1px solid #f0f0f0; font-size: 11px; vertical-align: middle; }
        
        .row-out-of-stock { background-color: #FFF5F5; } 
        .text-critical { color: #D93025 !important; }

        .badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 9px; 
            font-weight: bold; 
            text-transform: uppercase; 
            display: inline-block;
            text-align: center;
            min-width: 95px;
        }
        
        .bg-green { background-color: #E6F4EA; color: #1E8E3E; }
        .bg-yellow { background-color: #FFF8E1; color: #F9AB00; }
        .bg-red { background-color: #FCE8E6; color: #D93025; } 
        
        .text-right { text-align: right; }
        .product-name { font-weight: bold; color: #202124; font-size: 12px; }
        .category-text { color: #202124; font-weight: normal; }

        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 14px; margin-top: 5px;">Inventory Status Report</div>
        <div style="font-size: 11px; color: #666;">Generated on: {{ $generatedAt }}</div>
    </div>

    <table class="stats-table">
        <tr>
            <td class="stat-card">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">In Stock</div>
                <div class="stat-value">{{ $stats['inStock'] }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Low Stock</div>
                <div class="stat-value">{{ $stats['lowStock'] }}</div>
            </td>
            <td class="stat-card">
                <div class="stat-label">Out of Stock</div>
                <div class="stat-value">{{ $stats['outOfStock'] }}</div>
            </td>
        </tr>
    </table>

    <table class="product-table">
        <thead>
            <tr>
                <th style="width: 35%">Product Name</th>
                <th style="width: 20%">Category</th>
                <th style="width: 15%">Price</th>
                <th style="width: 10%">Stock</th>
                <th style="width: 20%" class="status-header">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            @php
                $isOut = $p->stock <= 0;
                $isLow = $p->stock > 0 && $p->stock <= 10;
                if($isOut) { $status = 'Out of Stock'; $badge = 'bg-red'; $rowClass = 'row-out-of-stock'; }
                elseif($isLow) { $status = 'Low Stock'; $badge = 'bg-yellow'; $rowClass = ''; }
                else { $status = 'In Stock'; $badge = 'bg-green'; $rowClass = ''; }
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="product-name {{ $isOut ? 'text-critical' : '' }}">{{ $p->name }}</td>
                <td class="category-text {{ $isOut ? 'text-critical' : '' }}">{{ $p->category }}</td>
                <td style="font-weight: bold;" class="{{ $isOut ? 'text-critical' : '' }}">&#8369;{{ number_format($p->price, 2) }}</td>
                <td style="font-weight: bold;" class="{{ $isOut ? 'text-critical' : '' }}">{{ $p->stock }}</td>
                <td class="text-right">
                    <span class="badge {{ $badge }}">{{ $status }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">        
        CocoHub - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>