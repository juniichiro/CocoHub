@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-5 pe-4 py-4 bg-[#738D56]/10 border-l-4 border-[#738D56] text-start text-sm font-extrabold text-[#738D56] rounded-r-2xl shadow-sm shadow-[#738D56]/5 focus:outline-none transition-all duration-200 ease-in-out'
            : 'block w-full ps-5 pe-4 py-4 border-l-4 border-transparent text-start text-sm font-bold text-gray-500 hover:text-[#738D56] hover:bg-gray-50/80 hover:border-[#738D56]/30 rounded-r-2xl focus:outline-none transition-all duration-200 ease-in-out mb-1';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center justify-between">
        <span>{{ $slot }}</span>
        @if($active)
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-50" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        @endif
    </div>
</a>