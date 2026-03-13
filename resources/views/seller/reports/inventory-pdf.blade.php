<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 0; }
        
        .header { text-align: center; border-bottom: 2px solid #738D56; padding-bottom: 10px; margin-bottom: 20px; }
        .brand-coco { color: #6D4C41; font-weight: bold; font-size: 24px; }
        .brand-hub { color: #738D56; font-weight: bold; font-size: 24px; }
        
        /* TABLE-BASED STATS ROW: Bulletproof single-row layout */
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
        
        /* Updated to be solid black for all values */
        .stat-value { font-size: 16px; font-weight: bold; color: #202124; }

        /* Product Table */
        table.product-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th { background: #738D56; color: white; padding: 12px 10px; font-size: 10px; text-align: left; text-transform: uppercase; letter-spacing: 0.05em; }
        th.status-header { text-align: center; }
        
        td { padding: 12px 10px; border-bottom: 1px solid #f0f0f0; font-size: 9px; vertical-align: middle; }
        
        /* Row Highlighting for Critical Status */
        .row-out-of-stock { background-color: #FFF5F5; } 
        .text-critical { color: #D93025 !important; }

        .badge { 
            padding: 4px 10px; 
            border-radius: 20px; 
            font-size: 8px; 
            font-weight: bold; 
            text-transform: uppercase; 
            display: inline-block;
            text-align: center;
            min-width: 85px;
        }
        
        .bg-green { background-color: #E6F4EA; color: #1E8E3E; }
        .bg-yellow { background-color: #FFF8E1; color: #F9AB00; }
        .bg-red { background-color: #FCE8E6; color: #D93025; } 
        
        .text-right { text-align: right; }
        .product-name { font-weight: bold; color: #202124; font-size: 10px; }
        .category-text { color: #9AA0A6; }

        .footer { margin-top: 30px; text-align: center; font-size: 8px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand-coco">Coco</span><span class="brand-hub">Hub</span>
        <div style="font-size: 12px; margin-top: 5px;">Inventory Status Report</div>
        <div style="font-size: 9px; color: #666;">Generated on: {{ $generatedAt }}</div>
    </div>

    {{-- Single Row Metrics --}}
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
                
                if($isOut) { 
                    $status = 'Out of Stock'; 
                    $badge = 'bg-red'; 
                    $rowClass = 'row-out-of-stock';
                } elseif($isLow) { 
                    $status = 'Low Stock'; 
                    $badge = 'bg-yellow'; 
                    $rowClass = '';
                } else { 
                    $status = 'In Stock'; 
                    $badge = 'bg-green'; 
                    $rowClass = '';
                }
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="product-name {{ $isOut ? 'text-critical' : '' }}">{{ $p->name }}</td>
                <td class="category-text">{{ $p->category }}</td>
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
        CocoHub Marketplace - For Educational Purposes Only. Developed by Lumiere.
    </div>
</body>
</html>