@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-3 pr-4 py-3 border-l-4 border-neon-purple text-left text-base font-bold text-white bg-gradient-to-r from-neon-purple/20 to-transparent focus:outline-none transition duration-300 ease-in-out relative overflow-hidden group'
            : 'block w-full pl-3 pr-4 py-3 border-l-4 border-transparent text-left text-base font-medium text-gray-400 hover:text-white hover:bg-white/5 hover:border-white/20 focus:outline-none transition duration-300 ease-in-out relative overflow-hidden group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
