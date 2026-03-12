@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        
        <header class="mb-10">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Your Cart</span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2">Shopping Cart</h1>
        </header>

        @if(session('status'))
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 animate-fade-in flex justify-between items-center">
                <span>
                    @if(session('status') === 'item-removed') Item removed from cart.
                    @elseif(session('status') === 'item-updated') Cart quantities updated.
                    @else {{ session('status') }} @endif
                </span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 animate-fade-in">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="flex flex-col lg:flex-row gap-10 items-start">
            
            <div class="w-full lg:w-2/3 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50">
                <h2 class="text-xl font-bold text-gray-800 mb-8">Order Summary</h2>
                
                <div class="space-y-8">
                    @php $subtotal = 0; @endphp
                    
                    @forelse($cart->items ?? [] as $item)
                        @php 
                            $itemTotal = $item->product->price * $item->quantity;
                            $subtotal += $itemTotal;
                        @endphp
                        <div class="flex items-center gap-6 pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="w-32 h-24 shrink-0 rounded-2xl overflow-hidden shadow-sm bg-gray-50">
                                <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.png')) }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <div class="flex-grow space-y-1">
                                <h3 class="text-lg font-bold text-gray-800">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-400 font-medium line-clamp-1">{{ $item->product->description }}</p>
                                
                                <div class="flex items-center gap-4 mt-3">
                                    <div class="inline-flex items-center bg-[#F9F7F2] rounded-full px-3 py-1 border border-gray-100 shadow-inner">
                                        <form action="{{ route('buyer.cart.remove', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <input type="hidden" name="decrement" value="1">
                                            <button type="submit" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-red-500 transition font-black">-</button>
                                        </form>
                                        
                                        <span class="px-4 text-sm font-bold text-gray-700">{{ $item->quantity }}</span>
                                        
                                        <form action="{{ route('buyer.cart.add', $item->product_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-[#738D56] transition font-black">+</button>
                                        </form>
                                    </div>

                                    <form action="{{ route('buyer.cart.remove', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] font-bold text-red-300 uppercase tracking-widest hover:text-red-500 transition ml-2">Remove Item</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-800">₱{{ number_format($itemTotal, 2) }}</span>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">₱{{ number_format($item->product->price, 2) }} ea</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-gray-400 font-medium italic">Your cart is empty.</p>
                            <a href="{{ route('buyer.product') }}" class="inline-block mt-4 text-[#738D56] font-bold text-xs uppercase tracking-widest border-b-2 border-[#738D56]/20 pb-1">Browse Products</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="w-full lg:w-1/3 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50 sticky top-28">
                <h2 class="text-xl font-bold text-gray-800 mb-8">Order Totals</h2>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-medium">Subtotal</span>
                        <span class="text-gray-800 font-bold">₱{{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pb-6 border-b border-gray-100">
                        @php $deliveryFee = $subtotal > 0 ? 80 : 0; @endphp
                        <span class="text-gray-400 font-medium">Delivery Fee</span>
                        <span class="text-gray-800 font-bold">₱{{ number_format($deliveryFee, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-bold text-gray-900">Total Due</span>
                        <span class="text-2xl font-black text-[#738D56]">₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                    </div>

                    <div class="space-y-3 pt-6">
                        <a href="{{ route('buyer.checkout') }}" 
                           class="block w-full py-4 bg-[#738D56] hover:bg-[#5f7547] text-white text-center font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 transition-all transform active:scale-95 {{ $subtotal <= 0 ? 'pointer-events-none opacity-50 grayscale cursor-not-allowed' : '' }}">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('buyer.product') }}" class="block w-full py-4 bg-white border border-gray-200 text-gray-400 text-center font-bold rounded-2xl hover:bg-gray-50 transition-all">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <x-buyer-footer />
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection