@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-black text-white shadow-lg transition-all duration-200 transform hover:scale-105 hover:shadow-xl border-2 border-white/30 backdrop-blur-sm'
            : 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200 transform hover:scale-105 border-2 border-transparent hover:border-white/20 backdrop-blur-sm';

$dynamicBg = $active ? 'background: linear-gradient(135deg, rgba(59, 130, 246, 0.9) 0%, rgba(99, 102, 241, 0.9) 100%);' : '';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active) style="{{ $dynamicBg }}" @endif>
    {{ $slot }}
</a>
