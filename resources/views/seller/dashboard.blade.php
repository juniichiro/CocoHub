@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]">
    
    <x-seller-sidebar />

    <main class="flex-grow ml-64 p-12 flex flex-col">
        
        <div class="flex-grow">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Seller Dashboard</p>
                    <h2 class="text-3xl font-bold text-gray-900">Seller's Workspace</h2>
                </div>
                <div class="flex gap-4 text-xs font-bold text-gray-400">
                    {{ now()->format('F d, Y') }}
                </div>
            </div>

            <div class="bg-white rounded-[3rem] p-12 mb-10 flex items-center justify-between border border-gray-50 shadow-sm overflow-hidden relative group">
                
                @if(isset($settings->main_image))
                    <img src="{{ asset('images/' . $settings->main_image) }}" class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-10 transition-opacity duration-700 pointer-events-none">
                @endif

                <div class="max-w-md space-y-6 z-10">
                    <h3 class="text-4xl font-bold text-gray-900 leading-tight">
                        Manage your coconut coir store with clarity and focus.
                    </h3>
                    <p class="text-sm text-gray-400 font-medium leading-relaxed">
                        Track your performance metrics and inventory status in real-time.
                    </p>
                    <div class="flex gap-4">
                        <a href="{{ route('seller.inventory') }}" class="px-8 py-3 bg-[#738D56] text-white font-bold rounded-xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95">
                            Manage Inventory
                        </a>
                        <a href="{{ route('seller.sales') }}" class="px-8 py-3 border border-gray-200 text-gray-400 font-bold rounded-xl hover:bg-gray-50 transition">
                            View Sales
                        </a>
                    </div>
                </div>

                <div class="w-1/2 flex justify-end z-10">
                    <div class="relative w-full max-w-md aspect-[4/3] overflow-hidden rounded-[2rem] shadow-2xl">
                        @if(isset($settings->main_image))
                            <img src="{{ asset('images/' . $settings->main_image) }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" 
                                 alt="Main Store Image">
                        @else
                            <img src="{{ asset('images/seller-banner.jpg') }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" 
                                 alt="Default Banner">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-[#738D56]/10 to-transparent pointer-events-none"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales Today</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">₱{{ number_format($salesToday, 2) }}</h4>
                    <p class="text-[10px] font-bold text-[#738D56] uppercase tracking-tight">Updated live</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Sales This Month</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">₱{{ number_format($salesThisMonth, 2) }}</h4>
                    <p class="text-[10px] font-bold text-[#738D56] uppercase tracking-tight">Current period</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Total Orders</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">{{ $totalOrders }}</h4>
                    <p class="text-[10px] font-bold text-[#738D56] uppercase tracking-tight">{{ $newOrdersToday }} new today</p>
                </div>

                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-md transition-shadow">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Low Stock Items</p>
                    <h4 class="text-3xl font-black text-gray-900 mb-2">{{ sprintf('%02d', $lowStockCount) }}</h4>
                    <p class="text-[10px] font-bold {{ $lowStockCount > 0 ? 'text-red-400' : 'text-[#738D56]' }} uppercase tracking-tight">
                        {{ $lowStockCount > 0 ? 'Needs replenishment' : 'Stock levels healthy' }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-8">
                <div class="xl:col-span-3 bg-white rounded-[2.5rem] p-8 border border-gray-50 shadow-sm">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="font-bold text-gray-800">Inventory Overview</h3>
                            <p class="text-xs text-gray-400">Latest products added</p>
                        </div>
                        <a href="{{ route('seller.inventory') }}" class="text-xs font-bold text-[#738D56] border border-[#738D56]/20 px-4 py-2 rounded-xl hover:bg-[#738D56]/5 transition">
                            Open Inventory
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] text-gray-300 uppercase tracking-widest">
                                    <th class="pb-4">Product</th>
                                    <th class="pb-4">Stock</th>
                                    <th class="pb-4 text-center">Price</th>
                                    <th class="pb-4 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($products as $product)
                                <tr class="border-t border-gray-50 hover:bg-gray-50/50 transition-colors">
                                    <td class="py-4">
                                        <p class="font-bold text-gray-800">{{ $product->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $product->category }}</p>
                                    </td>
                                    <td class="py-4 font-bold text-gray-800">{{ $product->stock }}</td>
                                    <td class="py-4 text-center text-gray-400 font-medium">₱{{ number_format($product->price, 2) }}</td>
                                    <td class="py-4 text-right">
                                        @if($product->stock <= 0)
                                            <span class="bg-red-100 text-red-600 text-[10px] font-bold px-3 py-1 rounded-lg uppercase">Out of Stock</span>
                                        @elseif($product->stock <= 10)
                                            <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-3 py-1 rounded-lg uppercase">Low Stock</span>
                                        @else
                                            <span class="bg-green-100 text-green-600 text-[10px] font-bold px-3 py-1 rounded-lg uppercase">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="xl:col-span-2 space-y-4">
                    <h3 class="font-bold text-gray-800 text-lg ml-2">Recent Orders</h3>
                    @foreach($recentOrders as $order)
                    @php $firstItem = $order->items->first(); @endphp
                    <div class="bg-white p-4 rounded-[2rem] border border-gray-50 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                        <img src="{{ asset('images/products/' . ($firstItem->product->image ?? 'placeholder.png')) }}" class="w-16 h-16 rounded-2xl object-cover" alt="Order">
                        <div class="flex-grow overflow-hidden">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $firstItem->product->name ?? 'Deleted Product' }}</p>
                            <p class="text-[10px] text-gray-400">Order #{{ $order->id }} · {{ $order->status }}</p>
                            <p class="text-[10px] text-gray-300">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[9px] text-gray-300 font-bold">{{ $order->items->sum('quantity') }}x</p>
                            <p class="text-xs font-bold text-gray-800">₱{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <x-seller-footer />
    </main>
</div>
@endsection