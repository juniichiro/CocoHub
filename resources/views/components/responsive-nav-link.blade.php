@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-4 pe-4 py-3 bg-[#738D56]/10 border-l-4 border-[#738D56] text-start text-sm font-bold text-[#738D56] rounded-r-xl focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-4 pe-4 py-3 border-l-4 border-transparent text-start text-sm font-bold text-gray-500 hover:text-[#738D56] hover:bg-gray-50 hover:border-gray-200 rounded-r-xl focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>