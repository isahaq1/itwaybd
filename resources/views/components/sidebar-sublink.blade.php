@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-2 bg-gray-700 text-white rounded-md'
            : 'block px-4 py-2 text-gray-400 hover:bg-gray-700 hover:text-white rounded-md';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>