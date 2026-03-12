@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]">
    <x-seller-sidebar />

    <div class="flex-grow ml-64 flex flex-col">
        
        <main class="p-8 lg:p-12 flex-grow">
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">Order Tracking</h1>
                    <p class="text-gray-500 text-sm font-bold uppercase tracking-widest mt-2">Manage and monitor customer shipments</p>
                </div>
                
                <div class="text-right">
                    <span class="text-[10px] font-black text-[#738D56] uppercase tracking-[0.2em] bg-white px-4 py-2 rounded-full border border-gray-100 shadow-sm">
                        {{ now()->format('F d, Y') }}
                    </span>
                </div>
            </header>

            @if(session('status'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold rounded-2xl animate-fade-in shadow-sm">
                    Order status updated successfully.
                </div>
            @endif

            <div class="bg-white p-6 rounded-[2.5rem] shadow-sm mb-10 border border-gray-100 flex gap-4">
                <form action="{{ route('seller.orders') }}" method="GET" class="flex flex-grow gap-4">
                    <input type="text" name="search" placeholder="Search Order # or Customer..." 
                           value="{{ request('search') }}"
                           class="flex-grow px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56] text-sm transition-all outline-none font-medium">
                    
                    <select name="status" class="px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-2xl focus:ring-2 focus:ring-[#738D56] text-sm cursor-pointer font-bold text-gray-600 outline-none">
                        <option value="">All Statuses</option>
                        <option value="Awaiting Shipping" {{ request('status') == 'Awaiting Shipping' ? 'selected' : '' }}>Awaiting Shipping</option>
                        <option value="On Delivery" {{ request('status') == 'On Delivery' ? 'selected' : '' }}>On Delivery</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    
                    <button type="submit" class="px-10 py-4 bg-[#738D56] text-white font-black uppercase tracking-widest text-[11px] rounded-2xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20 transform active:scale-95">
                        Apply Filters
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                            <th class="px-10 py-7 font-black">Order ID</th>
                            <th class="px-10 py-7 font-black">Customer Information</th>
                            <th class="px-10 py-7 font-black text-center">Items</th>
                            <th class="px-10 py-7 font-black text-center">Total Amount</th>
                            <th class="px-10 py-7 font-black text-center">Status</th>
                            <th class="px-10 py-7 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-[#F9F7F2]/30 transition-colors group">
                            <td class="px-10 py-6 font-black text-gray-900">#{{ $order->id }}</td>
                            
                            <td class="px-10 py-6 max-w-xs">
                                <p class="text-sm font-black text-gray-800 leading-tight">
                                    @if($order->user->buyerDetail)
                                        {{ $order->user->buyerDetail->first_name }} 
                                        {{ $order->user->buyerDetail->middle_name ? $order->user->buyerDetail->middle_name . ' ' : '' }}
                                        {{ $order->user->buyerDetail->last_name }}
                                    @else
                                        {{ $order->user->name }}
                                    @endif
                                </p>
                                
                                <p class="text-[10px] text-[#738D56] font-black uppercase tracking-tighter mt-1">
                                    {{ $order->user->buyerDetail->phone_number ?? 'No Contact' }}
                                </p>

                                <p class="text-[10px] text-gray-400 font-bold truncate italic mt-1" title="{{ $order->shipping_address }}">
                                    {{ $order->shipping_address }}
                                </p>
                            </td>

                            <td class="px-10 py-6 text-center">
                                <span class="bg-[#F9F7F2] text-gray-600 text-xs font-black px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                                    {{ $order->items->sum('quantity') }}
                                </span>
                            </td>

                            <td class="px-10 py-6 font-black text-[#738D56] text-center">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </td>

                            <td class="px-10 py-6 text-center">
                                <span class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest inline-block border
                                    {{ $order->status == 'Awaiting Shipping' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                                    {{ $order->status == 'On Delivery' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                                    {{ $order->status == 'Completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                    {{ $order->status == 'Cancelled' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            <td class="px-10 py-6 text-right">
                                <form action="{{ route('seller.orders.update', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <div class="relative inline-block">
                                        <select name="status" onchange="this.form.submit()" 
                                                class="appearance-none pr-10 pl-5 py-3 text-[11px] font-black bg-[#F9F7F2] border border-gray-100 rounded-xl focus:ring-2 focus:ring-[#738D56] cursor-pointer text-gray-700 outline-none uppercase tracking-tighter shadow-sm transition-all hover:border-[#738D56]/30">
                                            <option value="Awaiting Shipping" {{ $order->status == 'Awaiting Shipping' ? 'selected' : '' }}>Ship</option>
                                            <option value="On Delivery" {{ $order->status == 'On Delivery' ? 'selected' : '' }}>Deliver</option>
                                            <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Finish</option>
                                            <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancel</option>
                                        </select>
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-10 py-32 text-center">
                                <div class="flex flex-col items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-gray-400 font-black uppercase tracking-widest text-[10px]">No orders currently in the system</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>

        <x-seller-footer />
    </div>
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection