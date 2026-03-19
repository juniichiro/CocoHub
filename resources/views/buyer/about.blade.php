@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="min-h-screen w-full bg-[#F9F7F2] flex flex-col">
    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-8 lg:px-20 py-16 animate-fade-in-up">
        <div class="mb-12">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-widest block mb-2">About us</span>
            <h1 class="text-4xl font-bold text-gray-900">Learn About us</h1>
        </div>

        <div class="flex flex-col lg:flex-row items-stretch gap-12">
            <div class="w-full lg:w-1/2 bg-white rounded-[3rem] p-10 lg:p-16 shadow-sm border border-gray-50 flex flex-col justify-center">
                <h2 class="text-5xl font-bold text-[#6D4C41] mb-8">Coconut Coir</h2>
                <p class="text-gray-600 text-lg leading-relaxed text-justify font-medium">
                    Coconut coir, also known as coconut fiber, is a natural material derived from the outer husk of coconuts. It is widely known for its durability, eco-friendliness, and versatility in various industries such as gardening, construction, and home products.
                </p>
            </div>

            <div class="w-full lg:w-1/2">
                <div class="h-full min-h-[450px] rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white">
                    <img src="{{ asset('images/about.png') }}" alt="Sustainability" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                </div>
            </div>
        </div>
    </main>

    <section class="max-w-7xl mx-auto px-8 lg:px-20 py-24 border-t border-gray-100">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <div class="w-full lg:w-1/2 shrink-0">
                <div class="relative group">
                    <div class="absolute -bottom-4 -left-4 w-full h-full border-2 border-[#738D56]/20 rounded-[3rem] -z-10 transition-transform group-hover:-translate-x-2 group-hover:translate-y-2"></div>
                    <div class="rounded-[3rem] overflow-hidden shadow-xl border-8 border-white aspect-[16/10]">
                        <img src="{{ asset('images/lumiere.jpg') }}" alt="Team Lumiere" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-1/2 space-y-10">
                <div class="space-y-6">
                    <p class="text-gray-500 text-lg leading-relaxed font-medium text-justify">
                        This project aims to showcase the potential of coconut coir by presenting a modern e-commerce platform where users can explore and purchase coir-based products.
                    </p>
                </div>

                <div class="space-y-6">
                    <h3 class="text-3xl font-extrabold text-gray-900 tracking-tight">Meet the Team</h3>
                    
                    <div class="grid gap-4">
                        @php
                            $team = [
                                ['last' => 'Agustin', 'first' => 'Ren Ren A.'],
                                ['last' => 'Britos', 'first' => 'Raniel John T.'],
                                ['last' => 'Valera', 'first' => 'Tamiyah Gale C.'],
                            ];
                        @endphp

                        @foreach($team as $member)
                            <div class="flex items-center gap-4 group">
                                <div class="h-2 w-2 rounded-full bg-[#738D56] group-hover:scale-150 transition-transform"></div>
                                <p class="text-xl text-gray-700 font-bold tracking-tight">
                                    <span class="text-gray-900">{{ $member['last'] }},</span> 
                                    <span class="text-gray-500 font-medium">{{ $member['first'] }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="pt-4">
                    <span class="px-6 py-2 bg-[#F9F7F2] border border-[#738D56]/20 text-[#738D56] text-xs font-bold uppercase tracking-widest rounded-full">
                        Group Lumiere
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-8 lg:px-20 py-24">
        <div class="bg-white rounded-[3rem] p-12 lg:p-20 shadow-sm border border-gray-50">
            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-gray-900 border-b-4 border-[#738D56] inline-block pb-2">
                    References
                </h2>
                <p class="mt-6 text-gray-500 font-medium leading-relaxed">
                    Images and product inspirations were taken from the following sources:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                @php
                    $references = [
                        'Brush' => 'https://www.indiamart.com/proddetail/coconut-brush-19186937897.html?srsltid=AfmBOor2Cc2YlvUEm3dKWLkxLkLiqpwaJNW2-SaNcxt1rpKlt3zwW4qK',
                        'Geotextile' => 'http://siridepot.com/blog/post/geo-textiles.html',
                        'Hanging Pot' => 'https://glgarden.en.made-in-china.com/product/ROrtajYEvecf/China-Coco-Hanging-Planter-Square-Shape-Coco-Hanging-Basket-Flower-Pot.html',
                        'Rope' => 'https://www.indiamart.com/proddetail/4mm-coconut-coir-braided-rope-2857374936933.html',
                        'Mat' => 'https://www.williamkempf.com/',
                        'Fertilizer' => 'https://www.alibaba.com/product-detail/Natural-Coco-Peat-Powder-Premium-Coco_10000038463168.html',
                        'Plant pot' => 'https://www.tradeindia.com/products/eco-friendly-coir-pot-c8155008.html',
                        'Slipper' => 'https://cocopeatcocofiberaustralia.com/our-product/natural-fashion-product/',
                        'Bag' => 'https://www.roxyaustralia.com.au/products/straw-addiction-erjbt036-yef0',
                        'Tray' => 'https://www.alibaba.com/product-detail/Coconut-coir-nursery-seed-starter-tray_11000004129016.html',
                    ];
                @endphp

                @foreach($references as $label => $source)
                    <div class="flex items-start gap-4 group">
                        <div class="mt-2 h-1.5 w-1.5 rounded-full bg-[#738D56] group-hover:scale-150 transition-transform"></div>
                        <p class="text-gray-700 leading-tight">
                            <span class="font-bold text-gray-900 capitalize">{{ $label }}</span>
                            <span class="text-gray-400 mx-2">—</span>
                            <span class="text-[#738D56] font-medium break-all underline underline-offset-4 decoration-[#738D56]/30 hover:decoration-[#738D56]">
                                {{ $source }}
                            </span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-buyer-footer />
</div>
@endsection