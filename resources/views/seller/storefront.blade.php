@extends('layouts.app')

@section('title', 'Storefront Editor')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]">
    <x-seller-sidebar />

    <main class="flex-grow ml-64 p-12 flex flex-col">
        <div class="flex-grow">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Edit Storefront</p>
                    <h2 class="text-3xl font-bold text-gray-900">Storefront Editor</h2>
                </div>
                <a href="{{ route('buyer.home') }}" target="_blank" class="px-10 py-3 bg-[#738D56] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95">
                    View Live Site
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl border-l-4 border-red-500">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm font-bold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('seller.storefront.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    {{-- Text Content Section --}}
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Hero Section Badge</label>
                            <input type="text" name="banner_badge" value="{{ old('banner_badge', $settings->banner_badge ?? 'Our Collection') }}" 
                                class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl text-gray-600 font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all placeholder-gray-300" placeholder="e.g. New Arrival">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Homepage Banner Title</label>
                            <input type="text" name="banner_title" value="{{ old('banner_title', $settings->banner_title ?? '') }}" 
                                class="w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl text-gray-600 font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">Short Description</label>
                            <textarea name="short_description" rows="4" 
                                class="w-full px-6 py-4 bg-white border border-gray-100 rounded-3xl text-gray-600 font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all leading-relaxed">{{ old('short_description', $settings->short_description ?? '') }}</textarea>
                        </div>
                    </div>

                    {{-- Image Upload Section --}}
                    <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 flex flex-col">
                        <label class="block text-sm font-bold text-gray-700 mb-6">Main Image Banner</label>
                        <div onclick="document.getElementById('banner_input').click()" 
                             class="relative flex-grow border-2 border-dashed border-gray-100 rounded-[2rem] flex flex-col items-center justify-center group hover:border-[#738D56]/30 transition-colors cursor-pointer bg-[#F9F7F2]/30 overflow-hidden">
                            
                            @if(isset($settings->main_image))
                                <img src="{{ asset('images/' . $settings->main_image) }}" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity">
                            @endif

                            <div class="relative z-10 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-300 group-hover:text-[#738D56] transition-colors mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <p class="text-sm font-bold text-gray-400 group-hover:text-gray-600 transition-colors tracking-tight">Click to Upload Banner</p>
                            </div>
                            <input type="file" id="banner_input" name="main_image" class="hidden">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    @for($i = 1; $i <= 4; $i++)
                    @php 
                        $productField = 'featured_' . $i;
                        $badgeField = 'featured_badge_' . $i;
                        $selectedProduct = old($productField, $settings->$productField ?? '');
                        $currentBadge = old($badgeField, $settings->$badgeField ?? 'Featured');
                        
                        $badgeOptions = ['Featured', 'Best Seller', 'New Arrival', 'Limited', 'Sale', 'Eco-Friendly'];
                    @endphp
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50 space-y-4">
                        <h3 class="text-sm font-bold text-gray-800 border-b border-gray-50 pb-2">Slot {{ $i }}</h3>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Product</label>
                            <select name="{{ $productField }}" class="w-full px-4 py-3 bg-[#F9F7F2]/50 border border-transparent rounded-xl text-xs font-bold text-gray-600 outline-none appearance-none cursor-pointer focus:bg-white focus:border-[#738D56]/20 transition-all">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $selectedProduct == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Badge Label</label>
                            <select name="{{ $badgeField }}" class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 outline-none focus:ring-2 focus:ring-[#738D56]/10 transition-all cursor-pointer">
                                @foreach($badgeOptions as $option)
                                    <option value="{{ $option }}" {{ $currentBadge == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>

                <div class="flex justify-end gap-4">
                    <button type="submit" class="px-10 py-3 bg-[#738D56] text-white font-bold rounded-xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition">
                        Update Storefront
                    </button>
                </div>
            </form>
        </div>
        <x-seller-footer />
    </main>
</div>
@endsection