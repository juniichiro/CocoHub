@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 bg-[#738D56] text-white rounded-xl shadow-md shadow-[#738D56]/20 transition-all duration-300'
            : 'flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-[#738D56] hover:bg-[#738D56]/5 rounded-xl transition-all duration-300 group';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    {{ $slot }}
</a>