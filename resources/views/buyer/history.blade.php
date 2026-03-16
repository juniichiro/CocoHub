@extends('layouts.app')

@section('title', 'Order History')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="text-center md:text-left">
                <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-3 py-1 rounded-full">Account Activity</span>
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-3 leading-tight">History<br class="hidden md:block"> Transactions</h1>
            </div>
            
            <div class="w-full md:w-auto">
                <select class="w-full md:w-48 bg-white border border-gray-100 rounded-xl px-4 py-3 text-xs font-bold uppercase tracking-widest text-gray-500 shadow-sm focus:ring-[#738D56] focus:border-[#738D56]">
                    <option>All Orders</option>
                    <option>Pending</option>
                    <option>Shipped</option>
                    <option>Completed</option>
                </select>
            </div>
        </header>

        @if(session('status'))
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 animate-fade-in">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 animate-fade-in">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @forelse($orders as $order)
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50 flex flex-col gap-6 relative group hover:shadow-md transition-all duration-300">
                    
                    <div class="flex justify-between items-start border-b border-gray-50 pb-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Order Reference</p>
                            <h3 class="text-lg font-black text-gray-900">#{{ $order->id }}</h3>
                        </div>
                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border
                            {{ $order->status == 'Awaiting Shipping' ? 'bg-amber-50 text-amber-600 border-amber-100' : '' }}
                            {{ $order->status == 'On Delivery' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                            {{ $order->status == 'Completed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                            {{ $order->status == 'Cancelled' ? 'bg-red-50 text-red-600 border-red-100' : '' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="space-y-4 flex-grow">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 bg-[#F9F7F2]/40 p-3 rounded-2xl border border-gray-50/50">
                            <div class="w-14 h-14 shrink-0 rounded-xl overflow-hidden shadow-sm bg-white">
                                <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.png')) }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-black text-gray-800 truncate">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">
                                    {{ $item->quantity }} Units @ ₱{{ number_format($item->price, 2) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="pt-6 border-t border-gray-50 mt-auto flex justify-between items-end">
                        <div class="space-y-1">
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Transaction Date</p>
                            <p class="text-xs font-bold text-gray-700">{{ $order->created_at->format('M d, Y') }}</p>
                            <div class="pt-2">
                                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Paid</p>
                                <p class="text-xl font-black text-[#738D56]">₱{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            @if($order->status == 'Completed')
                                @if(!$order->is_rated)
                                    <button type="button" onclick="openRatingModal({{ $order->id }})" 
                                        class="w-full px-6 py-3 bg-amber-400 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-amber-500 transition-all shadow-lg shadow-amber-400/20 transform active:scale-95">
                                        Rate Products
                                    </button>
                                @else
                                    <button disabled class="w-full px-6 py-3 bg-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl cursor-not-allowed border border-gray-200">
                                        Already Rated
                                    </button>
                                @endif
                            @endif

                            @if($order->items->first())
                            <form action="{{ route('buyer.cart.add', $order->items->first()->product_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-6 py-3 bg-[#738D56] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20 transform active:scale-95">
                                    Buy Again
                                </button>
                            </form>
                            @endif
                            <button class="w-full px-6 py-3 bg-white text-gray-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-gray-100 hover:bg-gray-50 transition-all">
                                Get Receipt
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-white rounded-[2rem] shadow-sm flex items-center justify-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <p class="text-gray-400 font-black uppercase tracking-widest text-xs">No transactions to display</p>
                </div>
            @endforelse
        </div>
    </main>

    <x-buyer-footer />
</div>

<div id="ratingModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-6">
    <div class="bg-white rounded-[3rem] w-full max-w-lg p-10 shadow-2xl animate-fade-in">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Rate Products</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Order <span id="modalOrderIdText">#000</span></p>
            </div>
            <button onclick="closeRatingModal()" class="text-gray-300 hover:text-gray-500 transition p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('buyer.reviews.store') }}" method="POST" class="space-y-8"> 
            @csrf
            <input type="hidden" name="order_id" id="modalOrderIdInput">
            
            <div class="text-center space-y-4">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Your Overall Rating</p>
                
                <div class="flex flex-row-reverse justify-center gap-2 text-4xl star-rating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden peer" required>
                        <label for="star{{ $i }}" class="cursor-pointer text-gray-200 transition-colors duration-200 hover:text-yellow-300 peer-checked:text-yellow-400">
                            ★
                        </label>
                    @endfor
                </div>
            </div>

            <div class="space-y-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Write a Review</label>
                <textarea name="comment" rows="4" placeholder="How was your coconut product experience?" 
                    class="w-full px-6 py-4 bg-[#F9F7F2]/60 border-none rounded-[2rem] focus:ring-2 focus:ring-[#738D56] text-sm transition-all outline-none resize-none placeholder-gray-300"></textarea>
            </div>

            <button type="submit" class="w-full py-5 bg-[#738D56] text-white font-black uppercase tracking-widest text-[11px] rounded-2xl shadow-xl shadow-[#738D56]/20 transform active:scale-95 transition-all">
                Submit Feedback
            </button>
        </form>
    </div>
</div>

<script>
    function openRatingModal(orderId) {
        document.getElementById('modalOrderIdText').innerText = '#' + orderId;
        document.getElementById('modalOrderIdInput').value = orderId;
        document.getElementById('ratingModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeRatingModal() {
        document.getElementById('ratingModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    }
</script>

<style>
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    .star-rating input[type="radio"]:checked ~ label {
        color: #fbbf24; 
    }

    .star-rating label:hover ~ label,
    .star-rating label:hover {
        color: #fcd34d; 
    }
</style>
@endsection