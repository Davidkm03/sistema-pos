@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-sm transition-all duration-200 transform hover:scale-105'
            : 'inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 transform hover:scale-105';

$dynamicBg = $active ? 'background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);' : '';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active) style="{{ $dynamicBg }}" @endif>
    {{ $slot }}
</a>
