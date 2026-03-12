@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        <div class="mb-8">
            <a href="{{ route('buyer.product') }}" class="text-gray-400 font-bold text-xs uppercase tracking-widest hover:text-[#738D56] transition">
                &lt; Back to Products
            </a>
        </div>

        @if(session('status') === 'added-to-cart')
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 animate-fade-in flex justify-between items-center">
                <span>Success! {{ $product->name }} has been added to your cart.</span>
                <a href="{{ route('buyer.cart') }}" class="underline decoration-2 underline-offset-4 hover:text-gray-100">View Cart</a>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-16 items-center">
            <div class="w-full lg:w-1/2 aspect-square bg-white rounded-[3rem] p-12 shadow-sm border border-gray-50 flex items-center justify-center">
                <img src="{{ asset('images/products/' . ($product->image ?? 'placeholder.png')) }}" 
                     alt="{{ $product->name }}" 
                     class="max-w-full max-h-full object-contain hover:scale-105 transition-transform duration-700">
            </div>

            <div class="w-full lg:w-1/2 space-y-8">
                <div>
                    <span class="px-5 py-2 bg-[#F9F7F2] text-[#738D56] text-[10px] font-black uppercase tracking-widest rounded-full border border-gray-100">
                        {{ $product->category }}
                    </span>
                    <h1 class="text-5xl font-black text-gray-900 mt-6 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-3 mt-4">
                        <div class="flex text-yellow-400 text-sm tracking-widest">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($product->rating))
                                    <span>★</span>
                                @else
                                    <span class="text-gray-200">★</span>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-400 font-black text-[10px] uppercase tracking-widest">
                            {{ number_format($product->rating, 1) }} ({{ $product->review_count }} Reviews)
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-6xl font-black text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                    <p class="text-xs font-bold {{ $product->stock > 0 ? 'text-[#738D56]' : 'text-red-500' }} uppercase tracking-widest">
                        {{ $product->stock > 0 ? $product->stock . ' units available' : 'Out of Stock' }}
                    </p>
                </div>

                <div class="prose prose-sm text-gray-500 leading-relaxed">
                    <p class="text-lg leading-relaxed">{{ $product->description }}</p>
                </div>

                <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    @if($product->stock > 0)
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Select Quantity</label>
                        <div class="flex items-center w-32 bg-white rounded-2xl border border-gray-100 p-1 shadow-sm">
                            <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-[#738D56] transition">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                                   class="w-12 text-center border-none focus:ring-0 font-bold text-gray-700 bg-transparent [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-[#738D56] transition">+</button>
                        </div>
                    </div>
                    @endif

                    <div class="flex gap-4">
                        <button type="submit" 
                            {{ $product->stock <= 0 ? 'disabled' : '' }}
                            class="flex-grow py-5 bg-[#738D56] hover:bg-[#5f7547] text-white font-bold rounded-[1.5rem] shadow-xl shadow-[#738D56]/20 transition-all transform active:scale-95 disabled:bg-gray-200 disabled:shadow-none uppercase tracking-widest text-xs">
                            {{ $product->stock > 0 ? 'Add to Cart' : 'Currently Unavailable' }}
                        </button>
                        <button type="button" class="p-5 border-2 border-gray-100 rounded-[1.5rem] hover:bg-gray-50 transition text-gray-300 hover:text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="pt-8 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-[#738D56]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Quality Guaranteed</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Secure Shipping</span>
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