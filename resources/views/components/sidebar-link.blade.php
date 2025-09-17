@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 bg-cyan-800 text-white rounded-md shadow-md'
            : 'flex items-center px-4 py-2 text-white hover:bg-cyan-700 hover:text-white rounded-md transition-all duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>