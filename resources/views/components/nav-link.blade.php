@props(['active'])

@php
$classes = ($active ?? false)
            ? 'transition-colors duration-500 inline-flex items-center px-1 pt-1 border-b-2 border-tecsisa-yellow text-sm font-black leading-5 focus:outline-none focus:border-yellow-500 selection:bg-tecsisa-yellow/30'
            : 'transition-colors duration-500 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 hover:border-tecsisa-yellow/30 focus:outline-none selection:bg-tecsisa-yellow/30';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} 
   style="color: var({{ $active ? '--theme-nav-active' : '--theme-nav-inactive' }})"
   onmouseenter="this.style.color = 'var(--theme-nav-active)'"
   onmouseleave="if(!{{ $active ? 'true' : 'false' }}) this.style.color = 'var(--theme-nav-inactive)'">
    {{ $slot }}
</a>
