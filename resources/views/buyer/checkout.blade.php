@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    
    @include('layouts.navigation')

    @if(session('status') === 'cart-empty')
        <div class="fixed top-24 right-8 z-50 p-4 bg-red-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-500/20 animate-fade-in flex items-center gap-4">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Your cart is empty! Add items before checking out.</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white/60 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        
        <header class="mb-10">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Your Order</span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2">Checkout</h1>
        </header>

        @if($cart->items->isEmpty())
            <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-sm border border-gray-50 animate-fade-in">
                <div class="w-20 h-20 bg-[#F9F7F2] rounded-full flex items-center justify-center mx-auto mb-6 text-[#738D56]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Your cart is currently empty</h2>
                <p class="text-gray-400 mb-8 max-w-xs mx-auto font-medium">Add some sustainable coconut coir products to your cart before checking out.</p>
                <a href="{{ route('buyer.product') }}" class="inline-block px-10 py-4 bg-[#738D56] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95">
                    Browse Products
                </a>
            </div>
        @else
            <form action="{{ route('buyer.checkout.process') }}" method="POST" class="flex flex-col lg:flex-row gap-8 items-start">
                @csrf
                
                <div class="w-full lg:w-[60%] bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50">
                    <h2 class="text-xl font-bold text-[#6D4C41] mb-8">Customer Information</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Complete Name</label>
                            <input type="text" name="customer_name" 
                                value="{{ old('customer_name', 
                                    (Auth::user()->buyerDetail->first_name ?? '') . ' ' . 
                                    (Auth::user()->buyerDetail->middle_name ? Auth::user()->buyerDetail->middle_name . ' ' : '') . 
                                    (Auth::user()->buyerDetail->last_name ?? '') 
                                ) }}" 
                                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#738D56] outline-none transition-all font-bold text-gray-700 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Email Address</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly
                                class="w-full px-5 py-3.5 bg-[#F3F4F6] border-none rounded-2xl text-gray-400 font-medium outline-none cursor-not-allowed shadow-inner">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Mobile Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', Auth::user()->buyerDetail->phone_number ?? '') }}" 
                                placeholder="+63"
                                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#738D56] outline-none transition-all font-bold text-gray-700 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Shipping Address</label>
                            <textarea name="shipping_address" rows="3" required
                                class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#738D56] outline-none transition-all placeholder-gray-300 font-bold text-gray-700">@if(Auth::user()->buyerDetail){{ Auth::user()->buyerDetail->address }}@endif</textarea>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[40%] space-y-6" x-data="{ paymentCategory: 'Cash on Delivery' }">
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Payment & Fulfillment</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Payment Method</label>
                                <select x-model="paymentCategory" name="payment_method" required 
                                    class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl outline-none appearance-none cursor-pointer font-bold text-gray-700 focus:ring-2 focus:ring-[#738D56]">
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="Online Payment">Online Payment</option>
                                </select>
                            </div>

                            <div x-show="paymentCategory === 'Online Payment'" x-transition:enter="transition ease-out duration-300" class="space-y-3 pt-2">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Select Provider (Demo Only)</label>
                                <div class="flex flex-col gap-3">
                                    <button type="button" class="flex items-center gap-4 w-full p-3 border-2 border-blue-100 rounded-2xl bg-blue-50/20">
                                        <div class="w-14 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm">
                                            <img src="{{ asset('images/GCash-Logo.png') }}" alt="GCash" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-wider">GCash</p>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Receive via</label>
                                <select name="fulfillment_method" class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl outline-none appearance-none cursor-pointer font-bold text-gray-700 focus:ring-2 focus:ring-[#738D56]">
                                    <option value="Delivery">Delivery</option>
                                    <option value="Pickup">Pickup</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-800 mb-6">Order Summary</h2>
                        <div class="space-y-4">
                            @php 
                                $subtotal = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
                                $deliveryFee = 80;
                            @endphp
                            
                            <div class="max-h-40 overflow-y-auto mb-4 space-y-2 pr-2 custom-scrollbar">
                                @foreach($cart->items as $item)
                                <div class="flex justify-between text-xs font-medium text-gray-500">
                                    <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                    <span>₱{{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                </div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center text-sm pt-4 border-t border-gray-100">
                                <span class="text-gray-400 font-medium">Subtotal</span>
                                <span class="text-gray-800 font-bold">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-lg font-bold text-gray-900">Total Due</span>
                                <span class="text-2xl font-black text-[#738D56]">₱{{ number_format($subtotal + $deliveryFee, 2) }}</span>
                            </div>

                            <button type="submit" class="w-full mt-6 py-4 bg-[#738D56] hover:bg-[#5f7547] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 transition-all transform active:scale-95">
                                Place Order Now
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </main>

    <x-buyer-footer />
</div>

<style>
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
</style>
@endsection