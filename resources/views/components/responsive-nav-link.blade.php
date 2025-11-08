@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-3 border-l-4 border-blue-400 text-start text-base font-black text-white bg-gradient-to-r from-blue-600/30 to-indigo-600/30 focus:outline-none focus:bg-blue-600/40 rounded-r-xl shadow-lg backdrop-blur-sm transition-all duration-200'
            : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-bold text-white/80 hover:text-white hover:bg-white/10 hover:border-white/30 focus:outline-none focus:text-white focus:bg-white/10 rounded-r-xl transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
