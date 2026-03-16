@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="flex h-screen bg-[#F9F7F2] overflow-hidden">
    <x-seller-sidebar />

    <main class="flex-grow ml-64 flex flex-col h-full">
        
        <div class="p-12 pb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Feedback Management</p>
                    <h2 class="text-3xl font-bold text-gray-900">Customer Reviews</h2>
                </div>
            </div>
        </div>

        <div class="flex-grow overflow-y-auto px-12 custom-scrollbar">
            <div class="space-y-6 pb-12">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50 flex flex-col md:flex-row items-start gap-8 transition-all hover:shadow-md">
                        
                        <div class="w-24 h-24 rounded-3xl overflow-hidden shrink-0 border border-gray-100 bg-[#F9F7F2]">
                            <img src="{{ asset('images/products/' . ($review->product->image ?? 'placeholder.png')) }}" 
                                 alt="{{ $review->product?->name ?? 'Product' }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <div class="flex-grow w-full">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-2">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-lg font-bold text-gray-900 leading-tight">
                                            {{ $review->product?->name ?? 'Deleted Product' }}
                                        </h4>
                                        <span class="text-[10px] bg-gray-100 text-gray-400 px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter">
                                            Order #{{ $review->order_id }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[#738D56] text-xs font-bold">{{ $review->user->name ?? 'Anonymous' }}</span>
                                        <span class="text-gray-300 text-[10px]">•</span>
                                        <span class="text-gray-400 text-[10px] font-bold">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>

                                    <div class="mt-3 inline-block">
                                        <div class="bg-[#F9F7F2] px-5 py-3 rounded-2xl border border-[#738D56]/10 shadow-sm">
                                            <p class="text-gray-600 leading-relaxed font-medium italic text-sm">
                                                "{{ $review->comment }}"
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex gap-0.5 pt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-gray-100">
                        <div class="w-16 h-16 bg-[#F9F7F2] rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 font-bold">No feedback received from customers yet.</p>
                    </div>
                @endforelse

                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 bg-white">
            <x-seller-footer />
        </div>
    </main>
</div>
@endsection