@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-tecsisa-yellow text-start text-base font-medium text-tecsisa-yellow focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-400 group-hover:text-white hover:border-white/10 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
    {{ $slot }}
</a>
