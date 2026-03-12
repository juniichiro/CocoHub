@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        
        <header class="mb-10">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Your Order</span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2">Checkout</h1>
        </header>

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
                        <p class="text-[10px] text-gray-400 mt-1 ml-1 italic">Linked to your CocoHub account.</p>
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
                            placeholder="Enter your full delivery address here..."
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#738D56] outline-none transition-all placeholder-gray-300 font-bold text-gray-700">@if(Auth::user()->buyerDetail){{ Auth::user()->buyerDetail->address }}@endif</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Add Note to Seller (Optional)</label>
                        <textarea name="notes" placeholder="e.g., Please leave at the gate..." rows="3"
                            class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-[#738D56] outline-none transition-all placeholder-gray-300 font-bold text-gray-700"></textarea>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-[40%] space-y-6" x-data="{ paymentCategory: 'Cash on Delivery' }">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Payment & Fulfilment</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Payment Method</label>
                            <select x-model="paymentCategory" name="payment_method" required 
                                class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-2xl outline-none appearance-none cursor-pointer font-bold text-gray-700 focus:ring-2 focus:ring-[#738D56]">
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>

                        <div x-show="paymentCategory === 'Online Payment'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             class="space-y-3 pt-2">
                            
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Select Provider (Demo Only)</label>
                            
                            <div class="flex flex-col gap-3">
                                <button type="button" class="flex items-center gap-4 w-full p-3 border-2 border-blue-100 rounded-2xl bg-blue-50/20 hover:bg-blue-50/40 transition-all active:scale-[0.98] group">
                                    <div class="w-14 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm shrink-0 p-1.5 border border-blue-50">
                                        <img src="{{ asset('images/GCash-Logo.png') }}" alt="GCash" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-wider">GCash</p>
                                        <p class="text-[9px] font-bold text-blue-500 uppercase tracking-tighter">Instant Pay</p>
                                    </div>
                                </button>

                                <button type="button" class="flex items-center gap-4 w-full p-3 border-2 border-emerald-100 rounded-2xl bg-emerald-50/20 hover:bg-emerald-50/40 transition-all active:scale-[0.98] group">
                                    <div class="w-14 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm shrink-0 p-1.5 border border-emerald-50">
                                        <img src="{{ asset('images/Maya-Logo.png') }}" alt="Maya" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-wider">Maya</p>
                                        <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-tighter">Instant Pay</p>
                                    </div>
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
                        
                        <div class="max-h-40 overflow-y-auto mb-4 space-y-2 pr-2">
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
                        
                        <div class="flex justify-between items-center text-sm pb-4 border-b border-gray-100">
                            <span class="text-gray-400 font-medium">Delivery Fee</span>
                            <span class="text-gray-800 font-bold">₱{{ number_format($deliveryFee, 2) }}</span>
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
    </main>

    <x-buyer-footer />
</div>
@endsection