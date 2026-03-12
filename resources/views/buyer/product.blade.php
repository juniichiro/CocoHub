@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col">
    
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        
        <header class="mb-10">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Our Products</span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2">Select Products</h1>
        </header>

        @if(session('status') === 'added-to-cart')
            <div class="mb-8 p-4 bg-[#738D56] text-white text-sm font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 animate-fade-in flex justify-between items-center">
                <span>Success! Item added to your shopping cart.</span>
                <a href="{{ route('buyer.cart') }}" class="underline decoration-2 underline-offset-4 hover:text-gray-100">View Cart</a>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-12">
            
            <aside class="w-full lg:w-1/4 shrink-0">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-50 sticky top-28">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Filter Products</h2>
                    
                    <form action="{{ route('buyer.product') }}" method="GET" class="space-y-6">
                        <div class="space-y-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Product" 
                                class="w-full px-5 py-3.5 bg-[#F9F7F2]/60 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#738D56] text-sm transition-all outline-none placeholder-gray-400">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Category</label>
                            <select name="category" class="w-full px-5 py-3.5 bg-[#F9F7F2]/60 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-[#738D56] text-sm outline-none cursor-pointer text-gray-600">
                                <option value="">All Categories</option>
                                <option value="Household" {{ request('category') == 'Household' ? 'selected' : '' }}>Household</option>
                                <option value="Construction" {{ request('category') == 'Construction' ? 'selected' : '' }}>Construction</option>
                                <option value="Gardening" {{ request('category') == 'Gardening' ? 'selected' : '' }}>Gardening</option>
                                <option value="Home & Living" {{ request('category') == 'Home & Living' ? 'selected' : '' }}>Home & Living</option>
                                <option value="Fashion" {{ request('category') == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-4 bg-[#738D56] hover:bg-[#5f7547] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 transition-all duration-300 transform active:scale-95 mt-4">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </aside>

            <section class="flex-grow">
                <div class="mb-10 flex items-center">
                    <span class="inline-flex items-center gap-2 px-5 py-2 border border-gray-200 rounded-full text-[11px] font-bold text-gray-400 bg-white shadow-sm">
                        {{ $products->count() }} Products Found
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($products as $product)
                    <div class="bg-white rounded-[2.5rem] p-4 shadow-sm hover:shadow-2xl hover:shadow-gray-200/40 transition-all duration-500 group flex flex-col h-full">
                        
                        <a href="{{ route('buyer.product.show', $product->id) }}" class="relative aspect-square rounded-[2rem] overflow-hidden mb-6 bg-gray-50 block">
                            <img src="{{ asset('images/products/' . ($product->image ?? 'placeholder.png')) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 {{ $product->stock <= 0 ? 'grayscale opacity-60' : '' }}">
                        </a>
                        
                        <div class="px-2 space-y-3 flex-grow flex flex-col">
                            <a href="{{ route('buyer.product.show', $product->id) }}" class="block">
                                <h3 class="text-lg font-bold text-gray-800 leading-tight group-hover:text-[#738D56] transition-colors min-h-[3rem]">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <div class="flex text-yellow-400 text-[11px]">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($product->rating))
                                                <span>★</span>
                                            @else
                                                <span class="text-gray-200">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-bold text-gray-400">
                                        {{ number_format($product->rating, 1) }} ({{ $product->review_count }})
                                    </span>
                                </div>
                                <span class="text-2xl font-bold text-[#738D56]">₱{{ number_format($product->price, 2) }}</span>
                            </div>

                            <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST" class="mt-auto pt-4">
                                @csrf
                                <button type="submit" 
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}
                                    class="w-full py-4 {{ $product->stock <= 0 ? 'bg-gray-200 cursor-not-allowed' : 'bg-[#6D4C41] hover:bg-[#5a3e35]' }} text-white font-bold rounded-2xl transition-all duration-300 shadow-md shadow-[#6D4C41]/10 transform active:scale-95">
                                    {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                        @endforelse
                </div>
            </section>

        </div>
    </main>

    <x-buyer-footer />
</div>
@endsection