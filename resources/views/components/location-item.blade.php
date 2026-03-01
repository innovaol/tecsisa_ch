@props(['location', 'allRacks'])

<div x-data="{ open: false }" class="mb-2">
    <!-- Location Card -->
    <div class="bg-tecsisa-dark/40 border border-white/5 p-3 rounded-xl flex justify-between items-center group hover:border-tecsisa-yellow/30 transition-all">
        <div class="flex items-center gap-3">
            @if($location->children->count() > 0 || $allRacks->where('location_id', $location->id)->count() > 0)
                <button @click="open = !open" class="text-gray-500 hover:text-tecsisa-yellow transition-transform duration-200" :class="open ? 'rotate-90' : ''">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            @else
                <div class="w-4"></div>
            @endif
            
            <div @click="open = !open" class="cursor-pointer">
                <h4 class="text-white font-bold text-sm">{{ $location->name }}</h4>
                <div class="flex gap-2 mt-0.5">
                    <span class="text-[9px] text-gray-500 uppercase tracking-tighter">
                        {{ $location->children->count() }} sub-niveles
                    </span>
                    <span class="text-[9px] text-tecsisa-yellow/60 uppercase tracking-tighter font-black">
                        {{ $allRacks->where('location_id', $location->id)->count() }} Racks
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <button @click="openEditLocationModal(@js($location))" class="text-gray-500 hover:text-white transition p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            <form action="{{ route('catalog.locations.destroy', $location) }}" method="POST" onsubmit="return confirm('¿Eliminar esta ubicación?')">
                @csrf @method('DELETE')
                <button class="text-gray-500 hover:text-red-400 transition p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Recursive Children & Racks -->
    <div x-show="open" x-collapse x-transition class="ml-6 mt-2 pl-4 border-l border-white/10 space-y-2">
        <!-- Sub-Locations -->
        @foreach($location->children as $child)
            <x-location-item :location="$child" :allRacks="$allRacks" />
        @endforeach

        <!-- Racks in this specific location -->
        @foreach($allRacks->where('location_id', $location->id) as $rack)
            <div class="bg-black/40 border border-white/10 p-4 rounded-xl group hover:border-tecsisa-yellow/40 transition-all flex justify-between items-center">
                <div>
                    <h5 class="text-white font-black text-xs flex items-center gap-2">
                         <svg class="w-3 h-3 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                         {{ $rack->name }}
                    </h5>
                    <p class="text-[9px] text-gray-500 uppercase">{{ $rack->total_units }}U • {{ $rack->status }}</p>
                </div>
                <div class="flex gap-2">
                    <button @click="openEditRackModal(@js($rack))" class="text-gray-600 hover:text-white transition p-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('catalog.racks.destroy', $rack) }}" method="POST" onsubmit="return confirm('¿Eliminar este rack?')">
                        @csrf @method('DELETE')
                        <button class="text-gray-600 hover:text-red-400 transition p-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach

        @if($location->children->count() === 0 && $allRacks->where('location_id', $location->id)->count() === 0)
            <p class="text-[10px] text-gray-700 italic py-1">Ubicación vacía</p>
        @endif
    </div>
</div>
