@props(['active', 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 bg-gray-900 text-white rounded-md'
            : 'flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>