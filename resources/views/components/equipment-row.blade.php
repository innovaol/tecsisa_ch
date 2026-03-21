@props(['equipment', 'level' => 0])

<tr class="border-b border-theme last:border-0 transition-colors duration-500 hover:bg-theme-table-row-hover" :class="theme === 'light' ? 'hover:bg-slate-50' : ''">
    <!-- ID Interno con indentación jerárquica -->
    <td class="py-5 pl-8" style="padding-left: {{ 2 + ($level * 1.5) }}rem;">
        <div class="flex items-center gap-2">
            @if($level > 0)
                <svg class="w-4 h-4 text-theme-border flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            @endif
            <span class="font-mono text-tecsisa-yellow font-black text-xs">{{ $equipment->internal_id }}</span>
        </div>
    </td>

    <!-- Equipo / Modelo -->
    <td class="py-5 px-4 max-w-xs">
        <div class="flex flex-col">
            <span class="font-black text-sm uppercase transition-colors duration-500 line-clamp-1" :class="theme === 'light' ? 'text-slate-800' : 'text-white'" title="{{ $equipment->name }}">{{ $equipment->name }}</span>
            <span class="text-[9px] text-gray-500 uppercase font-bold tracking-widest line-clamp-1">{{ $equipment->location->name ?? 'Heredado / Sin ubicación' }}</span>
        </div>
    </td>

    <!-- Naturaleza -->
    <td class="py-5 px-4 hidden sm:table-cell">
        @if($equipment->form_factor === 'rackmount')
            <span class="text-[8px] bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg border border-blue-500/20 uppercase font-black tracking-widest whitespace-nowrap">Rack {{ $equipment->u_height }}U</span>
        @elseif($equipment->form_factor === 'peripheral')
            <span class="text-[8px] bg-purple-500/10 text-purple-400 px-2 py-1 rounded-lg border border-purple-500/20 uppercase font-black tracking-widest">Periférico</span>
        @else
            <span class="text-[8px] bg-emerald-500/10 text-emerald-400 px-2 py-1 rounded-lg border border-emerald-500/20 uppercase font-black tracking-widest">Red / Pared</span>
        @endif
    </td>

    <!-- Sistema -->
    <td class="py-5 px-4 hidden md:table-cell">
        <span class="text-xs font-bold uppercase transition-colors duration-500" :class="theme === 'light' ? 'text-slate-600' : 'text-gray-400'">{{ $equipment->system->name ?? 'N/A' }}</span>
    </td>

    <!-- Estatus -->
    <td class="py-5 px-4 text-center">
        <div class="flex items-center justify-center">
            @if($equipment->status === 'operative')
                <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]" title="Operativo"></div>
            @elseif($equipment->status === 'under_maintenance')
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.4)]" title="Mantenimiento"></div>
            @else
                <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.4)]" title="Fuera de Servicio"></div>
            @endif
        </div>
    </td>

    <!-- Acciones -->
    <td class="py-5 pr-8 text-right">
        <div class="flex items-center justify-end gap-2 relative z-10 transition-colors">
            <button @click="openEditModal(@js($equipment))" class="p-2.5 bg-theme-border border border-theme rounded-xl text-theme hover:text-tecsisa-yellow transition-all shadow-md active:scale-90">
                <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <form action="{{ route('catalog.equipment.destroy', $equipment) }}" method="POST" onsubmit="return confirm('¿Eliminar este equipo{{ $equipment->children->count() > 0 ? ' Y TODOS SUS SUB-PUERTOS' : '' }} del inventario?')">
                @csrf @method('DELETE')
                <button type="submit" class="p-2.5 bg-red-500/5 rounded-xl border border-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-md active:scale-90">
                    <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </td>
</tr>

@if($equipment->children && $equipment->children->count() > 0)
    @foreach($equipment->children as $child)
        <x-equipment-row :equipment="$child" :level="$level + 1" />
    @endforeach
@endif
