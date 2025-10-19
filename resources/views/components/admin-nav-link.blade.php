@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center p-3 rounded-lg bg-camture-green-light bg-opacity-25 text-white' // Style saat aktif
            : 'flex items-center p-3 rounded-lg text-gray-300 hover:bg-camture-green-light hover:bg-opacity-10 hover:text-white'; // Style normal
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>