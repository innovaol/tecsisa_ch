<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            {{ __('Gestión de Catálogos (BDR)') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'locations' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tabs Navigation -->
            <div class="flex space-x-2 mb-6 ml-2 overflow-x-auto">
                <button @click="activeTab = 'locations'" :class="{ 'bg-tecsisa-yellow text-tecsisa-dark font-bold shadow-[0_0_15px_rgba(255,209,0,0.3)]': activeTab === 'locations', 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10': activeTab !== 'locations' }" class="px-5 py-2.5 rounded-xl text-sm transition-all duration-300 border border-white/10">
                    Edificios y Áreas
                </button>
                <button @click="activeTab = 'systems'" :class="{ 'bg-tecsisa-yellow text-tecsisa-dark font-bold shadow-[0_0_15px_rgba(255,209,0,0.3)]': activeTab === 'systems', 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10': activeTab !== 'systems' }" class="px-5 py-2.5 rounded-xl text-sm transition-all duration-300 border border-white/10">
                    Sistemas Técnicos
                </button>
                <button @click="activeTab = 'equipment'" :class="{ 'bg-tecsisa-yellow text-tecsisa-dark font-bold shadow-[0_0_15px_rgba(255,209,0,0.3)]': activeTab === 'equipment', 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10': activeTab !== 'equipment' }" class="px-5 py-2.5 rounded-xl text-sm transition-all duration-300 border border-white/10">
                    Inventario Base Físico
                </button>
            </div>

            <!-- Content Area: Locations -->
            <div x-show="activeTab === 'locations'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-lg border border-white/10 overflow-hidden" style="display: none;">
                <div class="flex justify-between items-center p-6 border-b border-white/5 bg-white/5">
                    <h3 class="text-xl font-semibold text-white">Estructura Hospitalaria</h3>
                    <button class="bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 border border-blue-500/30 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        + Nueva Ubicación
                    </button>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold tracking-wide text-gray-400 uppercase border-b border-white/10">
                                    <th class="pb-3 pr-4">ID</th>
                                    <th class="pb-3 pr-4">Nombre de la Ubicación</th>
                                    <th class="pb-3 pr-4">Nivel Jurídico/Físico</th>
                                    <th class="pb-3 pr-4">Ubicación Padre</th>
                                    <th class="pb-3 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($locations as $loc)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="py-4 pr-4 font-mono text-xs text-gray-500">LOC-{{ str_pad($loc->id, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 pr-4 text-sm font-medium text-gray-200 group-hover:text-white">{{ $loc->name }}</td>
                                    <td class="py-4 pr-4 text-sm text-gray-400">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($loc->level === 'edificio') bg-purple-500/10 text-purple-400 border border-purple-500/20
                                            @elseif($loc->level === 'piso') bg-blue-500/10 text-blue-400 border border-blue-500/20
                                            @else bg-green-500/10 text-green-400 border border-green-500/20 @endif">
                                            {{ ucfirst($loc->level) }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-4 text-sm text-gray-500">{{ $loc->parent ? $loc->parent->name : '-- Raíz --' }}</td>
                                    <td class="py-4 text-right">
                                        <button class="text-gray-400 hover:text-tecsisa-yellow transition"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <button class="text-gray-400 hover:text-red-400 transition ml-2"><svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="py-6 text-center text-gray-500">No hay ubicaciones registradas</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Content Area: Systems -->
            <div x-show="activeTab === 'systems'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-lg border border-white/10 overflow-hidden" style="display: none;">
                <div class="flex justify-between items-center p-6 border-b border-white/5 bg-white/5">
                    <h3 class="text-xl font-semibold text-white">Sistemas Clínicos y Tecnológicos</h3>
                    <button class="bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 border border-blue-500/30 px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        + Registrar Sistema
                    </button>
                </div>
                <!-- Grids of System Cards instead of Tables for variety -->
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($systems as $sys)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-5 hover:border-tecsisa-yellow/30 transition group">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-bold text-gray-200 group-hover:text-tecsisa-yellow transition">{{ $sys->name }}</h4>
                            <span class="bg-white/10 text-gray-400 text-xs px-2 py-1 rounded">{{ count($sys->form_schema ?? []) }} Atributos</span>
                        </div>
                        <div class="space-y-2 mt-4">
                            @foreach(array_slice($sys->form_schema ?? [], 0, 3) as $field)
                                <div class="text-xs text-gray-400 font-mono bg-black/20 p-1.5 rounded flex justify-between">
                                    <span>{{ $field['label'] }}</span>
                                    <span class="text-gray-500">[{{ $field['type'] }}]</span>
                                </div>
                            @endforeach
                            @if(count($sys->form_schema ?? []) > 3)
                                <div class="text-xs text-center text-gray-500 w-full mt-2 italic">+ {{ count($sys->form_schema) - 3 }} campos más</div>
                            @endif
                        </div>
                        <div class="mt-6 flex justify-end space-x-2">
                             <button class="text-xs border border-white/10 text-gray-300 hover:bg-white/10 px-3 py-1.5 rounded transition">Editar Esquema</button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-6 text-center text-gray-500">No hay sistemas registrados</div>
                    @endforelse
                </div>
            </div>

            <!-- Content Area: Equipment -->
            <div x-show="activeTab === 'equipment'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-lg border border-white/10 overflow-hidden" style="display: none;">
                <div class="flex justify-between items-center p-6 border-b border-white/5 bg-white/5">
                    <h3 class="text-xl font-semibold text-white">Inventario (Asset DB)</h3>
                    <div class="flex space-x-3">
                        <div class="relative">
                            <input type="text" placeholder="Buscar por ID Interno..." class="bg-black/30 border border-white/10 text-sm text-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-tecsisa-yellow focus:border-tecsisa-yellow">
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <button @click="$dispatch('open-modal', 'create-equipment')" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-bold px-4 py-2 rounded-lg text-sm transition shadow-[0_0_10px_rgba(255,209,0,0.2)]">
                            + Alta de Equipo
                        </button>
                    </div>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-black/20 text-xs font-semibold tracking-wide text-gray-400 uppercase border-b border-white/10">
                                    <th class="py-4 pl-6 pr-4">ID de Placa</th>
                                    <th class="py-4 pr-4">Nombre / Modelo</th>
                                    <th class="py-4 pr-4">Tipo (Form Factor)</th>
                                    <th class="py-4 pr-4">Sistema</th>
                                    <th class="py-4 pr-4">Ubicación Actual</th>
                                    <th class="py-4 pr-4">Estatus</th>
                                    <th class="py-4 pr-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($equipments as $eq)
                                <tr class="hover:bg-white/5 transition-colors group cursor-pointer">
                                    <td class="py-4 pl-6 pr-4 font-mono text-sm tracking-tight text-tecsisa-yellow font-bold">{{ $eq->internal_id }}</td>
                                    <td class="py-4 pr-4 text-sm font-medium text-gray-200 group-hover:text-white">{{ $eq->name }}</td>
                                    <td class="py-4 pr-4 text-sm text-gray-400">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border 
                                            @if($eq->form_factor === 'rackmount') border-blue-500/30 text-blue-400 bg-blue-500/5
                                            @elseif($eq->form_factor === 'peripheral') border-purple-500/30 text-purple-400 bg-purple-500/5
                                            @else border-amber-500/30 text-amber-400 bg-amber-500/5 @endif">
                                            {{ $eq->form_factor }}
                                        </span>
                                    </td>
                                    <td class="py-4 pr-4 text-sm text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                            {{ $eq->system->name }}
                                        </div>
                                    </td>
                                    <td class="py-4 pr-4 text-sm text-gray-400">
                                        {{ $eq->location ? $eq->location->name : 'N/A' }}
                                    </td>
                                    <td class="py-4 pr-4 text-sm text-gray-400">
                                        @if($eq->status === 'operative')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Operativo</span>
                                        @elseif($eq->status === 'under_maintenance')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Mantenimiento</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">De Baja</span>
                                        @endif
                                    </td>
                                    <td class="py-4 pr-6 text-right flex justify-end gap-2">
                                        <button class="text-gray-400 hover:text-white transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <form action="{{ route('catalog.equipment.destroy', $eq) }}" method="POST" onsubmit="return confirm('¿Eliminar este equipo del inventario?')">
                                            @csrf @method('DELETE')
                                            <button class="text-gray-500 hover:text-red-400 transition p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="py-8 text-center text-gray-500">El catálogo de equipos está vacío.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                </div>
            </div>

        </div>
    </div>
    </div>

    <!-- Modal: Alta de Equipo -->
    <x-modal name="create-equipment" :show="false" maxWidth="4xl" focusable>
        <div x-data="equipmentForm(@js($systems))" 
             class="bg-tecsisa-dark p-0 border border-white/10 overflow-hidden">
            <form method="post" action="{{ route('catalog.equipment.store') }}" class="p-8">
            @csrf
            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Registrar Nuevo Activo (Asset)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ID Interno -->
                <div>
                    <x-input-label for="internal_id" value="ID de Placa / Tag" class="text-gray-400 text-xs font-bold uppercase" />
                    <x-text-input id="internal_id" name="internal_id" type="text" class="mt-1 block w-full bg-black/40 border-white/10 focus:border-tecsisa-yellow text-white" required placeholder="Ej: SW-MDF-001" />
                </div>

                <!-- Nombre -->
                <div>
                    <x-input-label for="name" value="Nombre del Equipo / Modelo" class="text-gray-400 text-xs font-bold uppercase" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-black/40 border-white/10 focus:border-tecsisa-yellow text-white" required placeholder="Ej: Cisco Catalyst 9300" />
                </div>

                <!-- Form Factor -->
                <div>
                    <x-input-label for="form_factor" value="Tipo de Activo (Form Factor)" class="text-gray-400 text-xs font-bold uppercase" />
                    <select id="form_factor" name="form_factor" class="mt-1 block w-full bg-black/40 border-white/10 rounded-md shadow-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow text-white">
                        <option value="rackmount">Rackmount (Switch/Servidor)</option>
                        <option value="peripheral">Periférico (Cámara/PC/AP)</option>
                        <option value="network_point">Punto de Red (Roseta/Pared)</option>
                    </select>
                </div>

                <!-- Sistema -->
                <div>
                    <x-input-label for="system_id" value="Sistema Perteneciente" class="text-gray-400 text-xs font-bold uppercase" />
                    <select id="system_id" name="system_id" x-model="system_id" class="mt-1 block w-full bg-black/40 border-white/10 rounded-md shadow-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow text-white">
                        <option value="">-- Seleccione Sistema --</option>
                        @foreach($systems as $sys)
                            <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Ubicación -->
                <div>
                    <x-input-label for="location_id" value="Ubicación Física" class="text-gray-400 text-xs font-bold uppercase" />
                    <select id="location_id" name="location_id" class="mt-1 block w-full bg-black/40 border-white/10 rounded-md shadow-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow text-white">
                        <option value="">Seleccione ubicación...</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Estatus -->
                <div>
                    <x-input-label for="status" value="Estado Operativo Inicial" class="text-gray-400 text-xs font-bold uppercase" />
                    <select id="status" name="status" class="mt-1 block w-full bg-black/40 border-white/10 rounded-md shadow-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow text-white">
                        <option value="operative">Operativo</option>
                        <option value="under_maintenance">En Mantenimiento</option>
                        <option value="out_of_service">Fuera de Servicio</option>
                    </select>
                </div>
            </div>

            <!-- SECCIÓN DINÁMICA: Especificaciones Técnicas del Sistema -->
            <div x-show="activeSchema.length > 0" x-transition class="mt-8 p-4 bg-white/5 border border-white/10 rounded-xl">
                <h3 class="text-sm font-bold text-tecsisa-yellow uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Especificaciones del Sistema
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="(field, index) in activeSchema" :key="index">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase mb-1" x-text="field.label"></label>
                            <input :type="field.type === 'number' ? 'number' : 'text'" 
                                   :name="'specs[' + field.label + ']'" 
                                   class="block w-full bg-black/60 border-white/5 rounded-lg text-sm text-white focus:border-tecsisa-yellow focus:ring-0 transition"
                                   :placeholder="'Ingresar ' + field.label.toLowerCase() + '...'">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Notas -->
            <div class="mt-6">
                <x-input-label for="notes" value="Notas Técnicas" class="text-gray-400 text-xs font-bold uppercase" />
                <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full bg-black/40 border-white/10 rounded-md shadow-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow text-white text-sm" placeholder="Detalles adicionales, número de serie, versión firmware..."></textarea>
            </div>

            <div class="mt-8 flex justify-end gap-3 text-white">
                <x-secondary-button x-on:click="$dispatch('close')" class="border-white/10 hover:bg-white/5">
                    Cancelar
                </x-secondary-button>

                <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-6 py-2 rounded-xl transition shadow-xl">
                    Guardar en Catálogo
                </button>
            </div>
        </form>
    </div>
</x-modal>

<script>
    function equipmentForm(systems) {
        return {
            system_id: systems.length > 0 ? systems[0].id : '',
            allSystems: systems,
            get activeSchema() {
                if (!this.system_id) return [];
                const found = this.allSystems.find(s => String(s.id) === String(this.system_id));
                return found ? (found.form_schema || []) : [];
            }
        };
    }
</script>
</x-app-layout>
