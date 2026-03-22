<x-app-layout>
    <script>
        window.catalogData = {
            locations: @json($locationsFlat),
            systems: @json($systems),
            racks: @json($racks),
            equipments: @json($allEquipments)
        };
    </script>
    @push('scripts')
        <!-- Flatpickr for Premium Date Selection -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
        <style>
            .flatpickr-calendar { background: var(--theme-card) !important; border-color: var(--theme-border) !important; box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important; color: var(--theme-text) !important; }
            .flatpickr-day.selected { background: #ffd100 !important; color: #000 !important; border-color: #ffd100 !important; }
            .flatpickr-day:hover { background: rgba(255,209,0,0.2) !important; }
            .flatpickr-month, .flatpickr-weekdays, .flatpickr-current-month { background: transparent !important; color: var(--theme-text) !important; }
            .flatpickr-monthDropdown-months, .flatpickr-day { color: var(--theme-text) !important; }
            input[type="date"]::-webkit-calendar-picker-indicator {
                filter: invert(var(--theme-invert));
                cursor: pointer;
            }
        </style>
    @endpush


    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8" x-data="inventoryManager(window.catalogData.locations, window.catalogData.systems, window.catalogData.racks, window.catalogData.equipments)">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-6 sm:p-8 mb-6 transition-all duration-500 shadow-xl relative">
            <!-- Decorative Orbs (Clipped) -->
            <div class="absolute inset-0 overflow-hidden rounded-[2.5rem] pointer-events-none">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            </div>
            <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                <a href="{{ route('dashboard') }}" class="md:hidden w-11 h-11 flex items-center justify-center bg-theme-card border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group shrink-0">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black transition-colors duration-500 leading-tight flex items-center gap-2" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        <span>Catálogo</span>
                        <div class="group relative inline-block">
                            <svg class="w-5 h-5 text-theme-muted cursor-help hover:text-tecsisa-yellow transition-colors overflow-visible" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="absolute sm:bottom-full top-full sm:top-auto left-1/2 -translate-x-1/2 sm:mb-3 mt-3 sm:mt-0 w-64 p-4 bg-black/95 text-[11px] text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-theme shadow-2xl normal-case font-bold backdrop-blur-md">
                                <div class="absolute sm:-bottom-1.5 -top-1.5 sm:top-auto left-1/2 -translate-x-1/2 w-3 h-3 bg-black/95 border-b sm:border-b border-r sm:border-r border-t sm:border-t-0 border-l sm:border-l-0 border-theme rotate-45"></div>
                                Gestión técnica de activos, especialidades y niveles de ubicación del hospital.
                            </div>
                        </div>
                    </h2>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 sm:mt-2 px-1">Equipos y sistemas</p>
                </div>
            </div>
        </div>

            
            <!-- Tabs Navigation -->
            <div class="bg-theme-card border border-theme rounded-3xl p-2 mb-8 transition-all duration-500 shadow-lg flex gap-1 sm:gap-2">
                <button @click="activeTab = 'equipment'" 
                        :class="activeTab === 'equipment' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'"
                        class="flex-1 px-3 sm:px-6 py-3 font-black transition-all rounded-2xl uppercase text-[9px] sm:text-[10px] tracking-wider sm:tracking-widest whitespace-nowrap text-center">
                    Activos
                </button>
                <button @click="activeTab = 'systems'" 
                        :class="activeTab === 'systems' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'"
                        class="flex-1 px-3 sm:px-6 py-3 font-black transition-all rounded-2xl uppercase text-[9px] sm:text-[10px] tracking-wider sm:tracking-widest whitespace-nowrap text-center">
                    Sistemas
                </button>
                <button @click="activeTab = 'locations'" 
                        :class="activeTab === 'locations' ? 'bg-tecsisa-yellow text-black' : 'text-gray-500 hover:text-slate-800 dark:hover:text-white'"
                        class="flex-1 px-3 sm:px-6 py-3 font-black transition-all rounded-2xl uppercase text-[9px] sm:text-[10px] tracking-wider sm:tracking-widest whitespace-nowrap text-center">
                    Ubicaciones
                </button>
            </div>

            <!-- Tab: Equipment (Main Catalog) -->
            <div x-show="activeTab === 'equipment'" x-transition class="space-y-8">
                <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-xl transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-wider transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Activos</h3>
                        <p class="text-gray-500 text-[10px] font-bold tracking-widest uppercase mt-1">Activos y hardware técnica</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        <div class="relative w-full sm:w-auto flex items-center">
                            <input type="text" placeholder="ID..." class="w-full bg-theme-card border border-theme text-[10px] font-bold text-gray-400 uppercase tracking-widest rounded-xl pl-10 pr-4 py-3 focus:border-tecsisa-yellow transition-all placeholder-gray-600 outline-none">
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        @if(Auth::user()->hasRole('Administrador'))
                        <button type="button" @click="openCreateModal()" class="w-full sm:w-auto flex justify-center items-center gap-2 bg-tecsisa-yellow hover:bg-yellow-400 text-black px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 relative z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                            Nuevo
                        </button>
                        @endif
                    </div>
                </div>

                <div class="bg-theme-card border border-theme rounded-[2rem] sm:rounded-[2.5rem] overflow-x-auto shadow-2xl transition-all duration-500 no-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/10 dark:bg-white/10 border-b border-theme text-[10px] font-black uppercase text-gray-500 tracking-[0.2em] transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50' : ''">
                                <th class="py-5 pl-8">Etiqueta</th>
                                <th class="py-5 px-4 font-black">Nombre del Equipo</th>
                                <th class="py-5 px-4 font-black">Naturaleza</th>
                                <th class="py-5 px-4 font-black">Sistema</th>
                                <th class="py-5 px-4 font-black text-center">Estatus</th>
                                <th class="py-5 pr-8 text-right font-black">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($equipments as $eq)
                            <tr class="border-b border-theme last:border-0 hover:bg-theme-table-row-hover transition-colors duration-500" :class="theme === 'light' ? 'hover:bg-slate-50' : ''">
                                <td class="py-5 pl-8">
                                    <span class="font-mono text-tecsisa-yellow font-black text-xs">{{ $eq->internal_id }}</span>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-black text-sm uppercase transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $eq->name }}</span>
                                        <span class="text-[9px] text-gray-500 uppercase font-bold tracking-widest">{{ $eq->location->name ?? 'Sin ubicación' }}</span>
                                    </div>
                                </td>
                                <td class="py-5 px-4">
                                    @if($eq->form_factor === 'rackmount')
                                        <span class="text-[8px] bg-blue-500/10 text-blue-400 px-2 py-1 rounded-lg border border-blue-500/20 uppercase font-black tracking-widest">Rack {{ $eq->u_height }}U</span>
                                    @else
                                        <span class="text-[8px] bg-gray-500/10 text-gray-400 px-2 py-1 rounded-lg border border-gray-500/20 uppercase font-black tracking-widest">Standalone</span>
                                    @endif
                                </td>
                                <td class="py-5 px-4 text-xs font-bold transition-colors duration-500" :class="theme === 'light' ? 'text-slate-600' : 'text-gray-400'">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-tecsisa-yellow uppercase text-[10px]">{{ $eq->system->name ?? 'N/A' }}</span>
                                        @if($eq->system->slug === 'NET-LINK')
                                            <div class="flex items-center gap-1 text-[8px] text-gray-500">
                                                <span class="opacity-50">De:</span> {{ $eq->source?->internal_id ?? '?' }}
                                                <span class="px-1 opacity-20">|</span>
                                                <span class="opacity-50">A:</span> {{ $eq->destination?->internal_id ?? '?' }}
                                            </div>
                                        @elseif($eq->system->slug === 'NET-HUB')
                                            <span class="text-[8px] text-gray-500 opacity-70">{{ $eq->port_capacity ?? '0' }} Puertos</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-5 px-4 text-center">
                                    <div class="flex items-center justify-center">
                                        @if($eq->status === 'operative')
                                            <div class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]" title="Operativo"></div>
                                        @elseif($eq->status === 'under_maintenance')
                                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.4)]" title="Mantenimiento"></div>
                                        @else
                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.4)]" title="Fuera de Servicio"></div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-5 pr-8 text-right">
                                    <div class="flex items-center justify-end gap-2 relative z-10">
                                        <button @click="openEditModal(@js($eq))" class="p-2.5 bg-theme-border border border-theme rounded-xl text-theme hover:text-tecsisa-yellow transition-all shadow-md active:scale-90">
                                            <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form action="{{ route('catalog.equipment.destroy', $eq) }}" method="POST" onsubmit="return confirm('¿Eliminar este equipo?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-red-500/5 rounded-xl border border-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-md active:scale-90">
                                                <svg class="w-5 h-5 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="py-8 text-center text-gray-500">El catálogo de activos está vacío.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'systems'" x-transition class="space-y-8">
                <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-xl transition-all duration-500 relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl pointer-events-none"></div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-wider transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Sistemas</h3>
                        <p class="text-gray-500 text-[10px] font-bold tracking-widest uppercase mt-1">Categorías de activos</p>
                    </div>
                    @if(Auth::user()->hasRole('Administrador'))
                    <button @click="openSystemModal()" class="w-full md:w-auto flex justify-center items-center gap-3 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark px-6 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95 group">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nuevo</span>
                    </button>
                    @endif
                </div>

                {{-- Grid Dinámico Continuo (Equilibrio 3/4) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($systems->take(3) as $sys)
                        @include('catalog.partials.system-card', ['sys' => $sys])
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($systems->skip(3) as $sys)
                        @include('catalog.partials.system-card', ['sys' => $sys])
                    @endforeach
                </div>
            </div>

            <!-- Tab: Locations -->
            <div x-show="activeTab === 'locations'" x-transition>
                <div class="max-w-4xl mx-auto space-y-6">
                    <!-- Title Card -->
                    <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-xl transition-all duration-500 relative overflow-hidden">
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-purple-500/5 rounded-full blur-2xl"></div>
                        <div>
                            <h3 class="text-2xl font-black uppercase tracking-wider transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Ubicaciones</h3>
                            <p class="text-gray-500 text-[10px] font-bold tracking-widest uppercase mt-1">Niveles y áreas técnicas</p>
                        </div>
                        @if(Auth::user()->hasRole('Administrador'))
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto relative z-10">
                            <button @click="openCreateLocationModal()" class="w-full sm:w-auto flex justify-center items-center gap-2 bg-theme-border border border-theme hover:bg-theme-table-row-hover text-gray-400 px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Nueva
                            </button>
                            <button @click="openCreateRackModal()" class="w-full sm:w-auto flex justify-center items-center gap-2 bg-tecsisa-yellow hover:bg-yellow-400 text-black px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-[0_10px_20px_rgba(255,191,0,0.2)] transition-all active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Nuevo
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Hierarchical Tree -->
                    <div class="space-y-4">
                        @foreach($locationsTree as $loc)
                            <x-location-item :location="$loc" :allRacks="$racks" />
                        @endforeach

                        @if($locationsTree->isEmpty())
                            <div class="text-center py-20 bg-white/5 rounded-3xl border border-dashed border-white/10">
                                <p class="text-gray-500">No hay ubicaciones registradas. Comienza creando una ubicación raíz.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        <!-- Location Modal -->
        <div x-show="showLocationModal" style="display: none;" class="fixed inset-0 z-[200] overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showLocationModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md bg-theme-card border border-theme rounded-3xl p-8 shadow-2xl transition-colors duration-500">
                    <form method="post" :action="locationFormAction">
                        @csrf
                        <template x-if="locationEditMode"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-theme" x-text="locationEditMode ? 'Editar Ubicación' : 'Nueva Ubicación'"></h2>
                            <button type="button" @click="showLocationModal = false" class="text-theme-muted hover:text-theme transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Nombre</label>
                                <input type="text" name="name" x-model="locationFormData.name" required class="w-full bg-theme-card border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition">
                            </div>
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Ubicación Padre (Jerarquía)</label>
                                <select name="parent_id" x-model="locationFormData.parent_id" 
                                        class="w-full bg-theme-card border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition shadow-sm">
                                    <option value="">-- Sin Padre (Raíz) --</option>
                                    @foreach($locationsFlat as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showLocationModal = false" class="px-6 py-2.5 rounded-xl text-theme-muted font-black uppercase text-[10px] tracking-widest hover:text-theme transition">Cancelar</button>
                            <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2.5 rounded-xl transition shadow-xl shadow-tecsisa-yellow/20 uppercase text-[10px] tracking-widest" x-text="'Guardar'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rack Modal -->
        <div x-show="showRackModal" style="display: none;" class="fixed inset-0 z-[200] overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="showRackModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-lg bg-theme-card border border-theme rounded-3xl p-8 shadow-2xl transition-colors duration-500">
                    <form method="post" :action="rackFormAction">
                        @csrf
                        <template x-if="rackEditMode"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-theme" x-text="rackEditMode ? 'Editar Rack' : 'Registrar Rack'"></h2>
                            <button type="button" @click="showRackModal = false" class="text-theme-muted hover:text-theme transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Nombre / Identificador</label>
                                <input type="text" name="name" x-model="rackFormData.name" required class="w-full bg-theme-card border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition" placeholder="Ej: RACK-MDF-01">
                            </div>
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Unidades Totales (U)</label>
                                <input type="number" name="total_units" x-model="rackFormData.total_units" min="1" max="52" required class="w-full bg-theme-card border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition">
                            </div>
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Ubicación</label>
                                <select name="location_id" x-model="rackFormData.location_id" required 
                                        class="w-full bg-theme-card dark:bg-black/20 border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition shadow-inner">
                                    <option value="">Seleccione site...</option>
                                    @foreach($locationsFlat as $l)
                                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Estado</label>
                                <select name="status" x-model="rackFormData.status" 
                                        class="w-full bg-theme-card dark:bg-black/20 border border-theme rounded-xl text-theme h-12 px-4 focus:ring-2 focus:ring-tecsisa-yellow transition shadow-inner">
                                    <option value="active">Active / Disponible</option>
                                    <option value="full">Lleno</option>
                                    <option value="maintenance">Mantenimiento</option>
                                </select>

                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Notas</label>
                                <textarea name="notes" x-model="rackFormData.notes" class="w-full bg-theme-card border border-theme rounded-xl text-theme p-4 h-24 text-sm focus:ring-2 focus:ring-tecsisa-yellow transition"></textarea>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showRackModal = false" class="px-6 py-2.5 rounded-xl text-theme-muted font-black uppercase text-[10px] tracking-widest hover:text-theme transition">Cancelar</button>
                            <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2.5 rounded-xl transition shadow-xl shadow-tecsisa-yellow/20 uppercase text-[10px] tracking-widest" x-text="'Guardar'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Modal (Managing Schemas) -->
        <div x-show="showSystemModal" 
             style="display: none;"
             class="fixed inset-0 z-[200] overflow-y-auto"
             role="dialog" aria-modal="true">
            
            <div x-show="showSystemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-all" @click="showSystemModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showSystemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-2xl bg-theme-card border border-theme rounded-3xl shadow-2xl overflow-hidden transition-all duration-500">
                    <div class="relative bg-theme-card">
                        
                        {{-- MODO A: Formulario de Edición (Sistemas Custom) --}}
                        <form x-show="!systemFormData.is_core" method="post" :action="systemFormAction" class="p-8" @submit.prevent="submitSystemForm($el)">
                            @csrf
                            <template x-if="systemEditMode">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                             <div class="flex justify-between items-center mb-6 border-b border-theme pb-4">
                                <h2 class="text-xl font-bold text-theme flex items-center gap-2">
                                    <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                    <span x-text="systemEditMode ? 'Editar Sistema Técnico' : 'Definir Nuevo Sistema'"></span>
                                </h2>
                                <button type="button" @click="showSystemModal = false" class="text-theme-muted hover:text-theme transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="space-y-6 max-h-[60vh] overflow-y-auto px-1 custom-scrollbar">
                                <div>
                                    <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Nombre del Sistema</label>
                                    <input type="text" name="name" x-model="systemFormData.name" required 
                                           class="w-full bg-theme-card border border-theme rounded-xl text-theme h-12 px-4 shadow-sm" 
                                           placeholder="Ej: CCTV, Control de Acceso, Redes...">
                                </div>

                                <input type="hidden" name="maintenance_interval_days" :value="systemFormData.maintenance_interval_days">
                                <input type="hidden" name="maintenance_guide" :value="systemFormData.maintenance_guide">

                                <div class="bg-theme-card rounded-2xl p-6 border border-theme">
                                    <div class="flex justify-between items-center mb-4 pb-2 border-b border-theme">
                                        <h3 class="text-xs font-black text-theme-muted uppercase tracking-widest">Esquema Técnico</h3>
                                        <button type="button" @click="addFieldToSchema()" class="text-[9px] bg-tecsisa-yellow text-tecsisa-dark px-3 py-1 rounded-lg font-black uppercase tracking-widest">+ Agregar</button>
                                    </div>

                                    <div class="space-y-3">
                                        <template x-for="(field, index) in systemFormData.form_schema" :key="index">
                                            <div class="flex gap-2 items-end">
                                                <div class="flex-1">
                                                    <input type="text" :name="'form_schema[' + index + '][label]'" x-model="field.label" class="w-full bg-theme-card border border-theme rounded-lg h-10 px-3 text-[10px] font-bold uppercase tracking-widest text-theme" placeholder="ETIQUETA">
                                                </div>
                                                <div class="w-32">
                                                    <select :name="'form_schema[' + index + '][type]'" x-model="field.type" class="w-full bg-theme-card border border-theme rounded-lg h-10 px-3 text-[10px] font-black uppercase tracking-widest transition text-theme">
                                                        <option value="text">Texto</option>
                                                        <option value="number">Número</option>
                                                        <option value="select">Dropdown</option>
                                                    </select>
                                                </div>
                                                <button type="button" @click="removeFieldFromSchema(index)" class="text-theme-muted hover:text-red-400 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="bg-emerald-500/5 rounded-2xl p-6 border border-emerald-500/10">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Protocolos</h3>
                                        <button type="button" @click="addChecklistItem()" class="text-[9px] bg-emerald-500 text-white px-3 py-1 rounded-lg font-black uppercase tracking-widest">+ Añadir</button>
                                    </div>
                                    <div class="space-y-2">
                                        <template x-for="(item, idx) in systemFormData.checklist" :key="idx">
                                            <div class="flex gap-2 items-center bg-black/5 dark:bg-black/20 p-2 rounded-xl transition-all border border-theme">
                                                <input type="text" data-checklist-input :value="item" class="flex-1 bg-transparent border-0 text-[10px] font-bold uppercase px-2 text-theme" placeholder="Describir actividad...">
                                                <button type="button" @click="removeChecklistItem(idx)" class="text-red-400 p-1 opacity-60 hover:opacity-100 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-theme">
                                <button type="button" @click="showSystemModal = false" class="px-6 py-3 rounded-xl text-theme-muted hover:text-theme font-bold text-[10px] uppercase tracking-widest transition">Cancelar</button>
                                <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-3 rounded-xl transition shadow-xl uppercase text-[10px] tracking-widest">Guardar Cambios</button>
                            </div>
                        </form>

                        {{-- MODO B: Ficha de Activo Premium (Human-Centric CORE) --}}
                        <div x-show="systemFormData.is_core" class="flex min-h-[450px] w-full transition-all duration-700">
                            
                            {{-- Barra de Identidad Violeta (Fiel a la tarjeta) --}}
                            <div class="w-2 shrink-0 bg-violet-600"></div>

                            <div class="flex-1 p-8 flex flex-col relative overflow-hidden bg-transparent">
                                {{-- Cabeza de la Ficha --}}
                                <div class="flex justify-between items-start mb-10 border-b border-theme pb-8 relative z-10 text-left">
                                    <div class="space-y-4 text-left">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-violet-500/10 text-violet-500 text-[10px] font-black uppercase tracking-widest border border-violet-500/20">
                                                Sistema de Infraestructura Crítica
                                            </span>
                                        </div>
                                        <h2 class="text-3xl font-black text-theme tracking-tight leading-none" x-text="systemFormData.name"></h2>
                                        <div class="flex items-center gap-3 text-[10px] text-theme-muted font-bold uppercase tracking-widest">
                                            <span>Tipo: <span class="text-theme uppercase">Infraestructura Core</span></span>
                                        </div>
                                    </div>
                                    <button @click="showSystemModal = false" class="w-10 h-10 rounded-2xl flex items-center justify-center bg-theme/5 text-theme-muted hover:text-red-500 transition-all border border-theme active:scale-95 shadow-sm">
                                        <svg class="w-5 h-5 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>

                                {{-- Cuerpo de la Ficha --}}
                                <div class="flex-1 overflow-y-auto pr-4 custom-scrollbar space-y-12 relative z-10">
                                    
                                    {{-- Bloque: Especificaciones Técnicas --}}
                                    <div class="space-y-6 text-left">
                                        <div class="flex items-center gap-3 border-l-4 border-theme pl-4 py-1">
                                            <h3 class="text-xs font-black text-theme uppercase tracking-widest">Especificaciones de Configuración</h3>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <template x-for="(field, index) in systemFormData.form_schema" :key="index">
                                                <div class="p-4 rounded-2xl bg-theme/5 border border-theme flex flex-col gap-2 group hover:bg-theme/10 transition-all shadow-sm hover:shadow-md">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-[9px] font-black text-theme-muted uppercase tracking-widest" x-text="field.label"></span>
                                                        <svg class="w-3.5 h-3.5 text-theme-muted opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    <span class="text-[11px] font-black text-theme uppercase tracking-tight" 
                                                          x-text="field.type === 'text' ? 'Campo de Texto' : 
                                                                  field.type === 'number' ? 'Valor Numérico' : 
                                                                  field.type === 'date' ? 'Fecha Calendario' : 
                                                                  field.type === 'select' ? 'Lista Desplegable' : field.type"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Bloque: Mantenimiento --}}
                                    <div class="mt-6 flex flex-col gap-3 text-left">
                                        <div class="flex items-center gap-3 border-l-4 border-emerald-500/50 pl-4 py-1">
                                            <h3 class="text-xs font-black text-theme uppercase tracking-widest">Actividades de Mantenimiento Preventivo</h3>
                                        </div>

                                        <div class="space-y-3 bg-emerald-500/5 p-6 rounded-3xl border border-emerald-500/10">
                                            <template x-for="(item, idx) in systemFormData.checklist" :key="idx">
                                                <div class="flex gap-4 items-center">
                                                    <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                                        <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    <p class="text-[11px] text-theme font-bold uppercase tracking-wide leading-relaxed" x-text="item"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- Pie de Ficha --}}
                                <div class="mt-8 pt-8 border-t border-theme flex items-center justify-end relative z-10 text-left">
                                    <button @click="showSystemModal = false" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl transition-all active:scale-95">
                                        Finalizar Consulta
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Modal Implementation (Integrated into root x-data scope) -->
        <div x-show="showEquipmentModal" 
             class="fixed inset-0 z-[200] overflow-y-auto"

             role="dialog" aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="showEquipmentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-all" @click="showEquipmentModal = false"></div>

            <!-- Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showEquipmentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-4xl bg-theme-card border border-theme rounded-3xl shadow-2xl overflow-hidden transition-all duration-500">
                    
                    <form method="post" :action="formAction" class="p-8" enctype="multipart/form-data">

                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                         <div class="flex justify-between items-center mb-6 border-b border-theme pb-4">
                            <h2 class="text-xl font-bold text-theme flex items-center gap-2">
                                <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span x-text="editMode ? 'Editar Activo: ' + formData.internal_id : 'Registrar Nuevo Activo (Asset)'"></span>
                            </h2>
                            <button type="button" @click="showEquipmentModal = false" class="text-theme-muted hover:text-theme transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Etiqueta -->
                            <div class="md:col-span-2">

                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Etiqueta</label>
                                <input type="text" name="internal_id" x-model="formData.internal_id" required 
                                       class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm" 
                                       placeholder="Ej: SW-MDF-001">
                            </div>


                            <!-- Número de Serie -->
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Número de Serie (S/N)</label>
                                <input type="text" name="serial_number" x-model="formData.serial_number" 
                                       class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm" 
                                       placeholder="Ej: FOC2345678">
                            </div>


                            <!-- Nombre -->
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Nombre del Equipo</label>
                                <input type="text" name="name" x-model="formData.name" required 
                                       class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm" 
                                       placeholder="Ej: Cisco Catalyst 9300">
                            </div>




                             <!-- Submit con flag de compatibilidad con backend -->
                            <select name="form_factor" id="form_factor_hidden" class="hidden">
                                <option value="standalone">standalone</option>
                                <option value="rackmount">rackmount</option>
                            </select>



                            <!-- Sistema -->
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Sistema Perteneciente</label>
                                <select name="system_id" x-model="formData.system_id" 
                                        class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm">
                                    <option value="">-- Seleccione Sistema --</option>
                                    <optgroup label="Sistemas de Infraestructura">
                                        @foreach($systems->where('is_core', true) as $sys)
                                            <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Sistemas Personalizados">
                                        @foreach($systems->where('is_core', false) as $sys)
                                            <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>

                            </div>



                            <!-- Ubicación -->
                            <div>
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Ubicación Física</label>
                                <select name="location_id" x-model="formData.location_id" 
                                        class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm">
                                    <option value="">Seleccione ubicación...</option>
                                    @foreach($locationsFlat as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>



                            <!-- Networking: HUB Capacity -->
                            <div x-show="getSelectedSystemSlug() === 'NET-HUB'" x-transition class="md:col-span-2 grid grid-cols-1 gap-4 bg-purple-500/5 p-4 rounded-xl border border-purple-500/10 mt-4">
                                <h4 class="text-[10px] font-black text-purple-500 uppercase tracking-widest">Capacidad del Nodo</h4>
                                <div>
                                    <label class="block text-theme-muted text-[9px] font-bold uppercase mb-1">Número de Bocas / Puertos Totales</label>
                                    <input type="number" name="port_capacity" x-model="formData.port_capacity" class="w-full bg-theme-card border border-theme rounded-xl text-xs h-10 px-3 transition" placeholder="Ej: 24">
                                </div>
                            </div>

                            <!-- Networking: Configuración de Enlace (Solo NET-LINK) -->
                            <div x-show="getSelectedSystemSlug() === 'NET-LINK'" x-transition 
                                 class="md:col-span-2 bg-slate-500/5 dark:bg-white/5 p-6 rounded-[2.5rem] border border-theme mt-6 space-y-6">
                                
                                <div class="flex items-center gap-3 border-b border-theme pb-4 mb-2">
                                    <div class="w-10 h-10 rounded-2xl bg-tecsisa-yellow/10 flex items-center justify-center text-tecsisa-yellow">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-black uppercase tracking-widest text-theme">Configuración de Conectividad</h3>
                                        <p class="text-[9px] text-theme-muted font-bold uppercase tracking-widest">Detalles del enlace lógico y físico</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Origen -->
                                    <div class="bg-blue-500/5 p-5 rounded-3xl border border-blue-500/10 space-y-4">
                                        <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-widest flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                            Punto de Origen (Distribución)
                                        </h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Equipo de Origen</label>
                                                <select name="source_equipment_id" x-model="formData.source_equipment_id" 
                                                        class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition shadow-sm text-theme">
                                                    <option value="">-- Sin Origen (Directo) --</option>
                                                    <template x-for="pEq in allEquipments" :key="pEq.id">
                                                        <option :value="pEq.id" x-text="pEq.internal_id + ' - ' + pEq.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Puerto / Posición</label>
                                                <input type="text" name="source_port" x-model="formData.source_port" class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition" placeholder="Ej: Puerto 01-A">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Destino -->
                                    <div class="bg-emerald-500/5 p-5 rounded-3xl border border-emerald-500/10 space-y-4">
                                        <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-2">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            Punto de Destino (Terminal)
                                        </h4>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Equipo Destino</label>
                                                <select name="destination_equipment_id" x-model="formData.destination_equipment_id" 
                                                        class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition shadow-sm text-theme">
                                                    <option value="">-- Sin Destino (Abierto) --</option>
                                                    <template x-for="pEq in allEquipments" :key="pEq.id">
                                                        <option :value="pEq.id" x-text="pEq.internal_id + ' - ' + pEq.name"></option>
                                                    </template>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Puerto / Salida</label>
                                                <input type="text" name="destination_port" x-model="formData.destination_port" class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition" placeholder="Ej: Roseta 3 / Toma A">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificación -->
                                <div class="bg-amber-500/5 p-5 rounded-3xl border border-amber-500/10 space-y-4">
                                    <h4 class="text-[10px] font-black text-amber-500 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Certificación de Enlace (Pruebas de Campo)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Estado de Prueba</label>
                                            <select name="certification_status" x-model="formData.certification_status" 
                                                    class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition shadow-sm text-theme">
                                                <option value="">Pendiente</option>
                                                <option value="certified">Certificado (PASS)</option>
                                                <option value="failed">FALLIDO (FAIL)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Fecha de Certif.</label>
                                            <input type="date" name="certification_date" x-model="formData.certification_date" 
                                                   class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition">
                                        </div>
                                        <div>
                                            <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Subir Reporte PDF</label>
                                            <div class="relative group">
                                                <input type="file" name="certification_pdf" 
                                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                                       @change="fileName = $event.target.files[0]?.name">
                                                <div class="w-full bg-theme-card border border-theme rounded-xl text-xs h-11 px-4 transition flex items-center gap-3 overflow-hidden group-hover:border-tecsisa-yellow/50">
                                                    <div class="bg-tecsisa-yellow/10 p-1.5 rounded-lg text-tecsisa-yellow">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    </div>
                                                    <span class="text-theme-muted truncate" x-text="fileName || 'Seleccionar archivo PDF...'"></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>




                            <!-- Estatus -->
                            <div class="md:col-span-2">
                                <label class="block text-theme-muted text-[10px] font-bold uppercase mb-1.5 tracking-widest">Estado Operativo</label>

                                <select name="status" x-model="formData.status" 
                                        class="w-full bg-theme-card border border-theme rounded-xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 shadow-sm">
                                    <option value="">-- Seleccione Estado --</option>
                                    <option value="operative">Operativo</option>
                                    <option value="under_maintenance">En Mantenimiento</option>
                                    <option value="out_of_service">Fuera de Servicio</option>
                                </select>
                            </div>



                             <!-- Rack Configuration Group (Relocated after Status) -->
                             <div class="md:col-span-2 bg-theme-card dark:bg-black/20 p-5 rounded-2xl border border-theme flex flex-col justify-center min-h-[96px]">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-3 cursor-pointer group leading-none">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" 
                                                   name="is_rackmount_bool"
                                                   x-model="formData.is_rackmount"
                                                   @change="document.getElementById('form_factor_hidden').value = $el.checked ? 'rackmount' : 'standalone'"
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-tecsisa-yellow rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 shadow-inner"></div>
                                        </div>
                                        <div class="flex flex-col gap-0.5">
                                            <span class="text-[10px] font-black text-theme-muted uppercase tracking-widest group-hover:text-theme transition-colors">Montaje en Rack</span>
                                            <span class="text-[8px] text-gray-500 font-bold uppercase tracking-widest">(Unidad para Cabinet)</span>
                                        </div>
                                    </label>
                                    
                                    <!-- Altura en U (Always occupies space, toggles visibility) -->
                                    <div :class="formData.is_rackmount ? 'opacity-100 translate-x-0 pointer-events-auto' : 'opacity-0 translate-x-4 pointer-events-none'" 
                                         class="flex flex-col items-end transition-all duration-300">
                                        <label class="text-[8px] font-black text-theme-muted uppercase mb-1">Altura (U)</label>
                                        <div class="flex items-center bg-black/10 dark:bg-black/40 border border-theme rounded-xl overflow-hidden shadow-inner h-10">
                                            <button type="button" @click="formData.u_height = Math.max(1, (formData.u_height || 1) - 1)" 
                                                    class="px-3 h-full hover:bg-red-500/20 text-theme-muted hover:text-red-400 transition flex items-center justify-center border-r border-theme">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"/></svg>
                                            </button>
                                            <input type="number" name="u_height" x-model="formData.u_height" min="1" max="42" readonly
                                                   class="w-12 bg-transparent border-none text-theme text-center font-black text-sm focus:ring-0 appearance-none pointer-events-none" 
                                                   style="-moz-appearance: textfield; appearance: textfield;">
                                            <button type="button" @click="formData.u_height = Math.min(42, (formData.u_height || 1) + 1)" 
                                                    class="px-3 h-full hover:bg-green-500/20 text-theme-muted hover:text-green-400 transition flex items-center justify-center border-l border-theme">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- SECCIÓN DINÁMICA: Especificaciones Técnicas -->
                        <div x-show="activeSchema.length > 0" x-transition class="mt-8 p-6 bg-black/10 dark:bg-black/40 border border-theme rounded-2xl transition-colors duration-500">
                            <h3 class="text-sm font-bold text-tecsisa-yellow uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Parámetros Técnicos
                            </h3>
                             <div class="grid grid-cols-1 gap-4" 
                                  :class="activeSchema.length === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2'">
                                <template x-for="(field, index) in activeSchema" :key="index">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-1" x-text="field.label"></label>
                                        
                                        <!-- Case: Select -->
                                        <template x-if="field.type === 'select'">
                                            <select :name="'specs[' + field.label + ']'" x-model="formData.specs[field.label]"
                                                    class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-sm text-theme focus:border-tecsisa-yellow focus:ring-2 focus:ring-tecsisa-yellow transition h-10 px-3 shadow-inner">
                                                <option value="">-- Seleccione --</option>
                                                <template x-for="(opt, idx) in getDropdownOptions(field.options)" :key="idx">
                                                    <option :value="opt" x-text="opt"></option>
                                                </template>
                                            </select>

                                        </template>

                                        <!-- Case: Date (Flatpickr) -->
                                        <template x-if="field.type === 'date'">
                                            <div class="relative">
                                                <input type="text" :name="'specs[' + field.label + ']'" 
                                                       x-init="flatpickr($el, { dateFormat: 'Y-m-d', allowInput: true, theme: 'dark', locale: 'es' })"
                                                       x-model="formData.specs[field.label]"
                                                       class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-sm text-theme focus:border-tecsisa-yellow focus:ring-0 transition h-9 px-3"
                                                       placeholder="YYYY-MM-DD">
                                                <div class="absolute right-3 top-2.5 text-theme-muted opacity-50 pointer-events-none">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Case: Long Text -->
                                        <template x-if="field.type === 'long_text'">
                                            <textarea :name="'specs[' + field.label + ']'" 
                                                   x-model="formData.specs[field.label]"
                                                   class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-sm text-theme focus:border-tecsisa-yellow focus:ring-0 transition p-3 h-24"
                                                   :placeholder="'Detalles de ' + field.label.toLowerCase()"></textarea>
                                        </template>

                                        <!-- Case: Number -->
                                        <template x-if="field.type === 'number'">
                                            <input type="number" :name="'specs[' + field.label + ']'" 
                                                   x-model="formData.specs[field.label]"
                                                   class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-sm text-theme focus:border-tecsisa-yellow focus:ring-0 transition h-9 px-3">
                                        </template>

                                        <!-- Case: Text (Default fallback) -->
                                        <template x-if="!['select', 'date', 'long_text', 'number'].includes(field.type)">
                                            <input type="text" :name="'specs[' + field.label + ']'" 
                                                   x-model="formData.specs[field.label]"
                                                   class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-sm text-theme focus:border-tecsisa-yellow focus:ring-0 transition h-10 px-3 shadow-inner"
                                                   placeholder="Ingrese valor...">
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="mt-6">
                            <label class="block text-theme-muted text-xs font-bold uppercase mb-1">Notas Técnicas</label>
                            <textarea name="notes" x-model="formData.notes" rows="3" 
                                      class="w-full bg-black/5 dark:bg-black/40 border border-theme rounded-lg text-theme focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition p-3 text-sm" 
                                      placeholder="Detalles adicionales..."></textarea>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showEquipmentModal = false" class="px-6 py-2 rounded-xl text-gray-400 transition font-bold uppercase text-xs" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                                Cancelar
                            </button>

                             <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2 rounded-xl transition shadow-xl shadow-yellow-400/10">
                                <span>Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function inventoryManager(locs, syss, rks, eqs) {
        const locations = locs || [];
        const systems = syss || [];
        const racks = rks || [];
        const equipments = eqs || [];

        const params = new URLSearchParams(window.location.search);
        return {
            activeTab: params.get('tab') || 'equipment',
            allLocations: locations,
            allSystems: systems,
            allRacks: racks,
            allEquipments: equipments,
            fileName: '',


            init() {
                this.$watch('activeTab', tab => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    history.replaceState(null, '', url.toString());
                });

                const freezeScroll = (val) => {
                    if(val) {
                        document.body.classList.add('overflow-hidden');
                        document.documentElement.classList.add('overflow-hidden');
                        setTimeout(() => {
                            document.querySelectorAll('.overflow-y-auto, .custom-scrollbar').forEach(el => el.scrollTop = 0);
                        }, 50);
                    } else {
                        document.body.classList.remove('overflow-hidden');
                        document.documentElement.classList.remove('overflow-hidden');
                    }
                };

                this.$watch('showEquipmentModal', freezeScroll);
                this.$watch('showSystemModal', freezeScroll);
                this.$watch('showLocationModal', freezeScroll);
                this.$watch('showRackModal', freezeScroll);
            },
            
            // Equipment Modal state
            showEquipmentModal: false,
            editMode: false,
            formAction: '/catalogos/equipment',
            formData: {
                id: '',
                internal_id: '',
                serial_number: '',
                name: '',
                form_factor: 'standalone',
                is_rackmount: false,
                u_height: 1,
                system_id: '',
                location_id: '',
                source_equipment_id: '',
                source_port: '',
                destination_equipment_id: '',
                destination_port: '',
                port_capacity: '',
                certification_pdf: '',
                certification_status: '',
                certification_date: '',
                status: 'operative',
                installation_date: '',
                last_maintenance_at: '',
                specs: {},
                notes: ''
            },


            getSelectedSystemSlug() {
                if (!this.formData.system_id) return null;
                const sys = this.allSystems.find(s => String(s.id) === String(this.formData.system_id));
                return sys ? sys.slug : null;
            },

            // Systems Modal state
            showSystemModal: false,
            systemEditMode: false,
            systemFormAction: '/catalogos/systems',
            systemFormData: {
                id: '',
                name: '',
                form_schema: [],
                checklist: [],
                maintenance_interval_days: 90,
                maintenance_guide: ''
            },

            // Locations Modal state
            showLocationModal: false,
            locationEditMode: false,
            locationFormAction: '/catalogos/locations',
            locationFormData: {
                id: '',
                name: '',
                parent_id: '',
                level: 1
            },

            // Racks Modal state
            showRackModal: false,
            rackEditMode: false,
            rackFormAction: '/catalogos/racks',
            rackFormData: {
                id: '',
                name: '',
                total_units: 42,
                location_id: '',
                status: 'active',
                notes: ''
            },

            get activeSchema() {
                if (!this.formData.system_id) return [];
                const sysId = String(this.formData.system_id);
                const found = this.allSystems.find(s => String(s.id) === sysId);
                return found ? (found.form_schema?.specs || found.form_schema || []) : [];
            },


            getDropdownOptions(optionsStr) {
                if (!optionsStr) return [];
                return String(optionsStr).split(',').map(s => s.trim()).filter(s => s !== '');
            },

            openCreateModal() {
                this.editMode = false;
                this.formAction = '/catalogos/equipment';
                this.formData = {
                    id: '', internal_id: '', serial_number: '', name: '', form_factor: 'standalone',
                    is_rackmount: false,
                    u_height: 1, system_id: '', location_id: '', status: 'operative', 
                    source_equipment_id: '', source_port: '',
                    destination_equipment_id: '', destination_port: '',
                    port_capacity: '',
                    certification_pdf: '', certification_status: '', certification_date: '',
                    installation_date: '', last_maintenance_at: '',
                    specs: {}, notes: ''
                };

                this.fileName = '';
                this.showEquipmentModal = true;
            },

            openEditModal(eq) {
                this.editMode = true;
                this.formAction = `/catalogos/equipment/${eq.id}`;
                this.formData = {
                    id: eq.id,
                    internal_id: String(eq.internal_id || ''),
                    serial_number: String(eq.serial_number || ''),
                    name: String(eq.name || ''),
                    form_factor: String(eq.form_factor || 'standalone'),
                    is_rackmount: (eq.form_factor === 'rackmount'),
                    u_height: eq.u_height || 1,

                    system_id: eq.system_id ? String(eq.system_id) : '',
                    location_id: eq.location_id ? String(eq.location_id) : '',
                    source_equipment_id: eq.source_equipment_id ? String(eq.source_equipment_id) : '',
                    source_port: String(eq.source_port || ''),
                    destination_equipment_id: eq.destination_equipment_id ? String(eq.destination_equipment_id) : '',
                    destination_port: String(eq.destination_port || ''),
                    port_capacity: eq.port_capacity || '',
                    certification_pdf: String(eq.certification_pdf || ''),
                    certification_status: String(eq.certification_status || ''),
                    certification_date: eq.certification_date ? String(eq.certification_date).split('T')[0] : '',
                    status: String(eq.status || 'operative'),
                    installation_date: eq.installation_date ? String(eq.installation_date).split('T')[0] : '',
                    last_maintenance_at: eq.last_maintenance_at ? String(eq.last_maintenance_at).split('T')[0] : '',
                    specs: eq.specs ? JSON.parse(JSON.stringify(eq.specs)) : {},
                    notes: String(eq.notes || '')
                };
                this.fileName = eq.certification_pdf ? eq.certification_pdf.split('/').pop() : '';
                this.showEquipmentModal = true;

            },

            // --- Systems Logic ---
            openCreateSystemModal() {
                this.systemEditMode = false;
                this.systemFormAction = '/catalogos/systems';
                this.systemFormData = {
                    id: '', 
                    name: '', 
                    form_schema: [],
                    checklist: [],
                    maintenance_interval_days: 90,
                    maintenance_guide: ''
                };
                this.showSystemModal = true;
            },

            openEditSystemModal(sys) {
                this.systemEditMode = true;
                this.systemFormAction = `/catalogos/systems/${sys.id}`;
                
                let schema = sys.form_schema || {};
                // Force parsing if it's a string (double encoded JSON scenario)
                if (typeof schema === 'string') {
                    try { schema = JSON.parse(schema); } catch (e) { schema = {}; }
                }

                const specFields = Array.isArray(schema) ? schema : (schema.specs || []);
                const checklistItems = Array.isArray(schema) ? [] : (schema.checklist || []);

                this.systemFormData = {
                    id: sys.id,
                    name: sys.name,
                    is_core: !!sys.is_core,
                    form_schema: JSON.parse(JSON.stringify(specFields)),
                    checklist: JSON.parse(JSON.stringify(checklistItems)),
                    maintenance_interval_days: sys.maintenance_interval_days || 90,
                    maintenance_guide: sys.maintenance_guide || ''
                };
                this.showSystemModal = true;
            },

            addFieldToSchema() {
                this.systemFormData.form_schema.push({ label: '', type: 'text', options: '' });
            },

            removeFieldFromSchema(index) {
                this.systemFormData.form_schema.splice(index, 1);
            },

            addChecklistItem() {
                this.systemFormData.checklist.push('');
            },

            removeChecklistItem(idx) {
                this.systemFormData.checklist.splice(idx, 1);
            },

            submitSystemForm(form) {
                // Limpiar hidden inputs previos
                form.querySelectorAll('[data-cl]').forEach(el => el.remove());

                // Leer directamente del DOM — garantiza el valor que el usuario tecleó
                const inputs = form.querySelectorAll('[data-checklist-input]');
                inputs.forEach((input, idx) => {
                    const val = input.value.trim();
                    if (val === '') return;
                    const hidden = document.createElement('input');
                    hidden.type  = 'hidden';
                    hidden.name  = `checklist[${idx}]`;
                    hidden.value = val;
                    hidden.setAttribute('data-cl', '1');
                    form.appendChild(hidden);
                });

                form.submit();
            },

            // --- Locations Logic ---
            openCreateLocationModal() {
                this.locationEditMode = false;
                this.locationFormAction = '/catalogos/locations';
                this.locationFormData = {
                    id: '', name: '', parent_id: '', level: 1
                };
                this.showLocationModal = true;
            },

            openEditLocationModal(loc) {
                this.locationEditMode = true;
                this.locationFormAction = `/catalogos/locations/${loc.id}`;
                this.locationFormData = {
                    id: loc.id,
                    name: loc.name,
                    parent_id: loc.parent_id ? String(loc.parent_id) : '',
                    level: loc.level || 1
                };
                this.showLocationModal = true;
            },

            // --- Racks Logic ---
            openCreateRackModal() {
                this.rackEditMode = false;
                this.rackFormAction = '/catalogos/racks';
                this.rackFormData = {
                    id: '', name: '', total_units: 42, location_id: '', status: 'active', notes: ''
                };
                this.showRackModal = true;
            },

            openEditRackModal(rack) {
                this.rackEditMode = true;
                this.rackFormAction = `/catalogos/racks/${rack.id}`;
                this.rackFormData = {
                    id: rack.id,
                    name: rack.name,
                    total_units: rack.total_units,
                    location_id: String(rack.location_id),
                    status: rack.status,
                    notes: rack.notes || ''
                };
                this.showRackModal = true;
            }
        };
    }


</script>
</x-app-layout>
