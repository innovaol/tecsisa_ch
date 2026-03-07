@if(isset($company_logo) && $company_logo)
    <img src="{{ asset('storage/' . $company_logo) }}" 
         {{ $attributes->merge(['class' => 'h-10 w-auto object-contain transition-all duration-300']) }} 
         :class="theme === 'light' ? 'drop-shadow-[0_0.5px_1px_rgba(0,0,0,0.8)]' : ''"
         alt="{{ $company_name }}">
@else
    <div {{ $attributes->merge(['class' => 'font-black text-2xl tracking-widest uppercase']) }}>
        <span :style="{ color: 'var(--theme-nav-active)' }">{{ substr($company_name ?? 'TECSISA', 0, 3) }}</span><span class="text-tecsisa-yellow">{{ substr($company_name ?? 'TECSISA', 3) }}</span>
    </div>
@endif
