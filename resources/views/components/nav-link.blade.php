@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-3 text-md font-bold rounded-lg text-white hover:bg-white/10 transition-all duration-200 focus:outline-none'
            : 'flex items-center px-4 py-3 text-md font-medium rounded-lg text-white/80 hover:bg-white/10 transition-all duration-200 focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
