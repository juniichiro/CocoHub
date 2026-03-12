@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="min-h-screen w-full bg-[#F9F7F2] flex flex-col">
    
    @include('layouts.navigation')

    <main class="w-full flex flex-col lg:flex-row items-center justify-between px-8 lg:px-20 py-12 lg:py-20 gap-12">
        
        <div class="w-full lg:w-1/2 flex flex-col space-y-6 animate-fade-in-up">
            <div>
                {{-- Dynamic Hero Badge --}}
                <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em] bg-[#738D56]/10 px-4 py-1.5 rounded-full">
                    {{ $settings->banner_badge ?? 'Our Collection' }}
                </span>
            </div>
            
            <h1 class="text-5xl lg:text-7xl font-bold text-gray-900 leading-[1.1] max-w-lg">
                {{ $settings->banner_title ?? 'Sustainable Coconut Coir Products for Everyday Use' }}
            </h1>

            <p class="text-gray-500 text-lg max-w-md leading-relaxed font-medium">
                {{ $settings->short_description ?? 'Browse curated coconut coir products for gardening, home, and construction.' }}
            </p>

            <div class="flex items-center gap-4 pt-4">
                <a href="#featured" class="px-10 py-4 bg-[#738D56] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95 text-center">
                    Featured
                </a>
                <a href="{{ route('buyer.product') }}" class="px-10 py-4 bg-white border border-gray-200 text-[#738D56] font-bold rounded-2xl shadow-sm hover:bg-gray-50 transition transform active:scale-95 text-center">
                    All Products
                </a>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex justify-center lg:justify-end shrink-0">
            <div class="relative w-full max-w-xl group">
                <div class="absolute -top-4 -right-4 w-full h-full border-2 border-[#738D56]/20 rounded-[3rem] -z-10 transition-transform group-hover:translate-x-2 group-hover:-translate-y-2"></div>
                
                <div class="aspect-[4/3] rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white">
                    @if(isset($settings->main_image))
                        <img src="{{ asset('images/' . $settings->main_image) }}" 
                             alt="Hero Banner" 
                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                    @else
                        <img src="{{ asset('images/hero.jpg') }}" 
                             alt="Default Hero" 
                             class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110">
                    @endif
                </div>
            </div>
        </div>
    </main>

    <section id="featured" class="px-8 lg:px-20 py-24 bg-white rounded-t-[4rem] shadow-[0_-20px_50px_-20px_rgba(0,0,0,0.05)]">
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Featured Curations</h2>
            <p class="text-gray-400 text-sm font-medium mt-2">Handpicked quality for your sustainable lifestyle.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                // Mapping the product relationships
                $featuredSlots = [
                    $settings->productOne ?? null,
                    $settings->productTwo ?? null,
                    $settings->productThree ?? null,
                    $settings->productFour ?? null,
                ];
            @endphp

            @foreach($featuredSlots as $index => $product)
                @if($product)
                @php
                    // Dynamically get the badge for this specific index (1 to 4)
                    $badgeKey = 'featured_badge_' . ($index + 1);
                    $badgeText = $settings->$badgeKey ?? 'Featured';
                @endphp
                <div class="bg-[#F9F7F2]/50 rounded-[2.5rem] p-4 transition-all duration-300 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 group">
                    <div class="relative aspect-square rounded-[2rem] overflow-hidden mb-6">
                        {{-- NEW: Individual Card Badge per Slot --}}
                        <span class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-[10px] font-bold text-gray-700 uppercase tracking-tight">
                            {{ $badgeText }}
                        </span>
                        <img src="{{ asset('images/products/' . ($product->image ?? 'placeholder.png')) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    
                    <div class="px-2 space-y-3">
                        <h3 class="text-lg font-bold text-gray-800 leading-tight">{{ $product->name }}</h3>
                        <div class="flex justify-start items-center">
                            <span class="text-2xl font-bold text-[#738D56]">₱{{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button class="w-full mt-2 py-3.5 bg-[#6D4C41] hover:bg-[#5a3e35] text-white font-bold rounded-2xl transition-colors duration-200 flex items-center justify-center gap-2 group/btn">
                                <span>Add to Cart</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </section>

    {{-- About section remains consistent --}}
    <section class="px-8 lg:px-20 py-24 bg-[#F9F7F2]">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <div class="w-full lg:w-1/2 space-y-6">
                <span class="text-[#738D56] text-xs font-bold uppercase tracking-widest">About</span>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                    Crafted from coconut fiber, <br> shaped with care.
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed max-w-lg font-medium">
                    CocoHub brings together sustainability and craftsmanship through a soft, earthy shopping experience. The visual direction is inspired by coconut coir textures, handmade warmth, and clean modern e-commerce design.
                </p>
            </div>

            <div class="w-full lg:w-1/2">
                <div class="aspect-[4/3] rounded-[3rem] overflow-hidden shadow-sm">
                    <img src="{{ asset('images/about.png') }}" alt="Sustainability" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <x-buyer-footer />
</div>

<style>
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
    }
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection