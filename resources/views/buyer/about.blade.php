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

    <x-buyer-footer />
</div>
@endsection