@php
    $isCore = $sys->is_core;
    $accentColor = $isCore ? '#7c3aed' : '#FFD100';
    $bgLight = $isCore ? '#f5f3ff' : '#ffffff';
    $cardId = "system-card-" . $sys->id;
@endphp

<style>
    .{{ $cardId }} {
        background-color: {{ $bgLight }};
        border: 1px solid #e2e8f0;
        border-left: 6px solid {{ $accentColor }};
    }
    .{{ $cardId }} .title-text { 
        color: {{ $isCore ? '#4c1d95' : '#0f172a' }}; 
    }
    .dark .{{ $cardId }} {
        background-color: {{ $isCore ? 'rgba(124, 58, 237, 0.1)' : 'rgba(255, 255, 255, 0.05)' }} !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-left: 6px solid {{ $accentColor }} !important;
    }
    .dark .{{ $cardId }} .title-text { 
        color: #ffffff !important; 
    }
</style>

<div class="{{ $cardId }} group relative rounded-3xl p-6 flex flex-col justify-between transition-all duration-300 hover:shadow-xl overflow-hidden min-h-[300px] h-full">
    <div class="relative z-10 flex flex-col h-full">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-6 font-primary">
            <div class="flex-1 pr-6">
                <div class="flex items-center gap-2 mb-1">
                    <h4 class="title-text font-black text-xl tracking-tight transition-colors duration-300">
                        {{ $sys->name }}
                    </h4>
                    @if($isCore)
                        <svg class="w-4 h-4" style="color: {{ $accentColor }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    @endif
                </div>
            </div>

            @if($isCore)
                <span class="text-[9px] px-3 py-1 rounded-lg font-black uppercase tracking-widest text-white shadow-lg shrink-0"
                      style="background-color: {{ $accentColor }} !important;">CORE</span>
            @endif
        </div>

        {{-- Schema: Pastillas Técnicas --}}
        <div class="mb-6 flex-1">
            <p class="text-[9px] text-slate-400 dark:text-gray-500 uppercase font-black tracking-widest mb-4 opacity-60">Estructura del Sistema:</p>
            <div class="flex flex-wrap gap-2.5">
                @php
                    $fields = $sys->form_schema['specs'] ?? (isset($sys->form_schema[0]['label']) ? $sys->form_schema : []);
                @endphp
                @forelse($fields as $field)
                    <div class="px-2.5 py-1 rounded-lg border bg-white/50 dark:bg-black/20 flex items-center gap-2 shadow-sm"
                         style="border-color: rgba({{ $isCore ? '124, 58, 237' : '226, 232, 240' }}, 0.2);">
                        <span class="text-[11px] font-bold text-slate-700 dark:text-gray-200">{{ $field['label'] ?? 'Campo' }}</span>
                        <span class="text-[9px] font-black uppercase" style="color: {{ $accentColor }};">[{{ $field['type'] ?? 'txt' }}]</span>
                    </div>
                @empty
                    <span class="text-[10px] text-slate-400 italic">Estructura estandarizada</span>
                @endforelse
            </div>
        </div>

        {{-- Línea Divisoria Separada --}}
        <div class="w-full h-px bg-slate-200 dark:bg-white/10 mb-6 mt-auto"></div>

        {{-- Footer: Acciones --}}
        <div class="flex items-center justify-between pb-1">
            @if($isCore)
                <div class="flex items-center gap-2" style="color: {{ $accentColor }};">
                    <div class="w-2 h-2 rounded-full bg-current animate-pulse shadow-[0_0_8px_rgba(124,58,237,0.5)]"></div>
                    <span class="text-[11px] font-black uppercase tracking-widest">Protegido</span>
                </div>
                <button @click="openEditSystemModal(@js($sys))" 
                        class="text-[11px] font-black uppercase tracking-widest text-white px-5 py-2.5 rounded-xl transition-all hover:brightness-125 dark:hover:scale-105 active:scale-95 shadow-xl shrink-0"
                        style="background-color: #000000 !important;">
                    Detalles
                </button>
            @else
                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold">
                    <div class="w-2 h-2 rounded-full bg-current"></div>
                    <span class="text-[11px] uppercase tracking-widest">Activo</span>
                </div>
                <div class="flex gap-3">
                    <button @click="openEditSystemModal(@js($sys))" 
                            class="w-12 h-12 flex items-center justify-center rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('catalog.systems.destroy', $sys) }}" method="POST" onsubmit="return confirm('¿Eliminar sistema?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
