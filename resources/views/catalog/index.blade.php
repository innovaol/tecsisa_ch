<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Gestión de Catálogos y Activos') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="inventoryManager(@js($locations), @js($systems))">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tabs Navigation -->
            <div class="flex gap-4 mb-8 border-b border-white/10 pb-4">
                <button @click="activeTab = 'locations'" 
                        :class="activeTab === 'locations' ? 'text-tecsisa-yellow border-b-2 border-tecsisa-yellow' : 'text-gray-500 hover:text-gray-300'"
                        class="px-4 py-2 font-bold transition-all uppercase text-sm tracking-widest">
                    Ubicaciones / Racks
                </button>
                <button @click="activeTab = 'systems'" 
                        :class="activeTab === 'systems' ? 'text-tecsisa-yellow border-b-2 border-tecsisa-yellow' : 'text-gray-500 hover:text-gray-300'"
                        class="px-4 py-2 font-bold transition-all uppercase text-sm tracking-widest">
                    Sistemas Técnicos
                </button>
                <button @click="activeTab = 'equipment'" 
                        :class="activeTab === 'equipment' ? 'text-tecsisa-yellow border-b-2 border-tecsisa-yellow' : 'text-gray-500 hover:text-gray-300'"
                        class="px-4 py-2 font-bold transition-all uppercase text-sm tracking-widest">
                    Inventario de Equipos
                </button>
            </div>

            <!-- Tab: Locations -->
            <div x-show="activeTab === 'locations'" x-transition>
                <div class="bg-tecsisa-dark/40 backdrop-blur-md border border-white/10 rounded-2xl p-8">
                    <h3 class="text-white text-lg font-bold mb-4">Ubicaciones y Site Rooms</h3>
                    <p class="text-gray-400 text-sm">Próximamente: CRUD de Ubicaciones</p>
                </div>
            </div>

            <!-- Tab: Systems -->
            <div x-show="activeTab === 'systems'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-white text-xl font-bold">Sistemas de Baja Tensión</h3>
                        <p class="text-gray-500 text-sm">Define los sistemas y sus parámetros técnicos personalizados.</p>
                    </div>
                    <button @click="openCreateSystemModal()" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-bold px-4 py-2 rounded-lg text-sm transition shadow-[0_0_10px_rgba(255,209,0,0.2)]">
                        + Nuevo Sistema
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($systems as $sys)
                    <div class="bg-tecsisa-dark/40 backdrop-blur-md p-6 rounded-2xl border border-white/10 group hover:border-tecsisa-yellow/50 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="font-bold text-lg text-white group-hover:text-tecsisa-yellow transition-colors">{{ $sys->name }}</h4>
                            <div class="flex gap-2">
                                <button @click="openEditSystemModal(@js($sys))" class="text-gray-500 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('catalog.systems.destroy', $sys) }}" method="POST" onsubmit="return confirm('¿Eliminar este sistema? No podrá eliminarse si tiene equipos asociados.')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-600 hover:text-red-400 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Esquema de Datos:</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($sys->form_schema ?? [] as $field)
                                    <span class="text-[10px] bg-white/5 border border-white/10 px-2 py-1 rounded text-gray-400">
                                        {{ $field['label'] }} <span class="text-tecsisa-yellow/50">({{ $field['type'] }})</span>
                                    </span>
                                @empty
                                    <span class="text-[10px] text-gray-600 italic">Sin campos personalizados</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab: Equipment (Main Catalog) -->
            <div x-show="activeTab === 'equipment'" x-transition>
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-white text-xl font-bold">Catálogo Maestro de Activos</h3>
                        <p class="text-gray-500 text-sm">Gestiona todo el hardware, periféricos y puntos de red.</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="relative">
                            <input type="text" placeholder="Buscar por ID Interno..." class="bg-black/30 border border-white/10 text-sm text-gray-300 rounded-lg pl-10 pr-4 py-2 focus:ring-tecsisa-yellow focus:border-tecsisa-yellow">
                            <svg class="w-4 h-4 text-gray-500 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <button @click="openCreateModal()" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-bold px-4 py-2 rounded-lg text-sm transition shadow-[0_0_10px_rgba(255,209,0,0.2)]">
                            + Alta de Equipo
                        </button>
                    </div>
                </div>

                <div class="bg-tecsisa-dark/60 border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-[10px] font-black uppercase text-gray-500 tracking-widest border-b border-white/10">
                                <th class="py-4 pl-6">ID Interno</th>
                                <th class="py-4">Nombre / Modelo</th>
                                <th class="py-4">Tipo (Form Factor)</th>
                                <th class="py-4">Sistema</th>
                                <th class="py-4">Estatus</th>
                                <th class="py-4 pr-6 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($equipments as $eq)
                            <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors group">
                                <td class="py-4 pl-6">
                                    <span class="font-mono text-tecsisa-yellow font-bold">{{ $eq->internal_id }}</span>
                                </td>
                                <td class="py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-200">{{ $eq->name }}</span>
                                        <span class="text-[10px] text-gray-500 uppercase">{{ $eq->location->name ?? 'Sin ubicación' }}</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    @if($eq->form_factor === 'rackmount')
                                        <span class="text-[10px] bg-blue-500/10 text-blue-400 px-2 py-0.5 rounded border border-blue-500/20 uppercase font-bold">Rackmount ({{ $eq->u_height }}U)</span>
                                    @elseif($eq->form_factor === 'peripheral')
                                        <span class="text-[10px] bg-purple-500/10 text-purple-400 px-2 py-0.5 rounded border border-purple-500/20 uppercase font-bold">Periférico</span>
                                    @else
                                        <span class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/20 uppercase font-bold">Red (Pared)</span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <span class="text-gray-400 font-medium">{{ $eq->system->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    @if($eq->status === 'operative')
                                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block shadow-[0_0_8px_rgba(34,197,94,0.6)]" title="Operativo"></span>
                                    @elseif($eq->status === 'under_maintenance')
                                        <span class="w-2 h-2 rounded-full bg-yellow-500 inline-block shadow-[0_0_8px_rgba(234,179,8,0.6)]" title="Mantenimiento"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-red-500 inline-block shadow-[0_0_8px_rgba(239,68,68,0.6)]" title="Fuera de Servicio"></span>
                                    @endif
                                </td>
                                <td class="py-4 pr-6 text-right flex justify-end gap-2">
                                    <button @click="openEditModal(@js($eq))" class="text-gray-400 hover:text-white transition p-1 cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
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

        <!-- System Modal (Managing Schemas) -->
        <div x-show="showSystemModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog" aria-modal="true">
            
            <div x-show="showSystemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-all" @click="showSystemModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showSystemModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-2xl bg-tecsisa-dark border border-white/10 rounded-2xl shadow-2xl overflow-hidden transition-all">
                    
                    <form method="post" :action="systemFormAction" class="p-8">
                        @csrf
                        <template x-if="systemEditMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                                <span x-text="systemEditMode ? 'Editar Sistema Técnico' : 'Definir Nuevo Sistema'"></span>
                            </h2>
                            <button type="button" @click="showSystemModal = false" class="text-gray-500 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Nombre del Sistema</label>
                                <input type="text" name="name" x-model="systemFormData.name" required 
                                       class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3" 
                                       placeholder="Ej: CCTV, Control de Acceso, Redes...">
                            </div>

                            <div class="bg-white/5 rounded-xl p-6 border border-white/5">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Esquema Técnico (Campos Dinámicos)</h3>
                                    <button type="button" @click="addFieldToSchema()" class="text-[10px] bg-tecsisa-yellow/10 text-tecsisa-yellow border border-tecsisa-yellow/20 px-3 py-1 rounded-full font-bold hover:bg-tecsisa-yellow/20 transition">
                                        + Agregar Campo
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="(field, index) in systemFormData.form_schema" :key="index">
                                        <div class="flex gap-2 items-end bg-black/20 p-3 rounded-lg border border-white/5">
                                            <div class="flex-1">
                                                <label class="text-[10px] text-gray-600 block mb-1">Etiqueta (Label)</label>
                                                <input type="text" :name="'form_schema[' + index + '][label]'" x-model="field.label" 
                                                       class="w-full bg-black/40 border-white/10 rounded-lg text-xs text-white h-8 px-2" placeholder="Ej: Resolución, IP, Piso...">
                                            </div>
                                            <div class="w-32">
                                                <label class="text-[10px] text-gray-600 block mb-1">Tipo de Dato</label>
                                                <select :name="'form_schema[' + index + '][type]'" x-model="field.type"
                                                        class="w-full bg-black/40 border-white/10 rounded-lg text-xs text-white h-8 px-2">
                                                    <option value="text">Texto</option>
                                                    <option value="number">Número</option>
                                                </select>
                                            </div>
                                            <button type="button" @click="removeFieldFromSchema(index)" class="text-gray-600 hover:text-red-400 p-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <template x-if="systemFormData.form_schema.length === 0">
                                        <p class="text-center text-xs text-gray-600 py-4 italic">No has definido campos técnicos para este sistema.</p>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showSystemModal = false" class="px-6 py-2 rounded-xl text-gray-400 hover:text-white transition font-bold uppercase text-xs">
                                Cancelar
                            </button>

                            <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2 rounded-xl transition shadow-xl shadow-yellow-400/10">
                                <span x-text="systemEditMode ? 'Actualizar Sistema' : 'Crear Sistema'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Custom Modal Implementation (Integrated into root x-data scope) -->
        <div x-show="showEquipmentModal" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog" aria-modal="true">
            
            <!-- Backdrop -->
            <div x-show="showEquipmentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-all" @click="showEquipmentModal = false"></div>

            <!-- Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showEquipmentModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative w-full max-w-4xl bg-tecsisa-dark border border-white/10 rounded-2xl shadow-2xl overflow-hidden transition-all">
                    
                    <form method="post" :action="formAction" class="p-8">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span x-text="editMode ? 'Editar Activo: ' + formData.internal_id : 'Registrar Nuevo Activo (Asset)'"></span>
                            </h2>
                            <button type="button" @click="showEquipmentModal = false" class="text-gray-500 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ID Interno -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">ID de Placa / Tag</label>
                                <input type="text" name="internal_id" x-model="formData.internal_id" required 
                                       class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3" 
                                       placeholder="Ej: SW-MDF-001">
                            </div>

                            <!-- Nombre -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Nombre del Equipo / Modelo</label>
                                <input type="text" name="name" x-model="formData.name" required 
                                       class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3" 
                                       placeholder="Ej: Cisco Catalyst 9300">
                            </div>

                            <!-- Form Factor -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Tipo de Activo (Form Factor)</label>
                                <select name="form_factor" x-model="formData.form_factor" 
                                        class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3">
                                    <option value="">-- Seleccione Tipo --</option>
                                    <option value="rackmount">Rackmount (Switch/Servidor)</option>
                                    <option value="peripheral">Periférico (Cámara/PC/AP)</option>
                                    <option value="network_point">Punto de Red (Roseta/Pared)</option>
                                </select>
                            </div>

                            <!-- Altura en U (Solo Rackmount) -->
                            <div x-show="formData.form_factor === 'rackmount'" x-transition>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Altura (Unidades de Rack - U)</label>
                                <input type="number" name="u_height" x-model="formData.u_height" min="1" max="42"
                                       class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3" 
                                       placeholder="Ej: 1, 2, 4...">
                            </div>

                            <!-- Sistema -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Sistema Perteneciente</label>
                                <select name="system_id" x-model="formData.system_id" 
                                        class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3">
                                    <option value="">-- Seleccione Sistema --</option>
                                    @foreach($systems as $sys)
                                        <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Ubicación Física</label>
                                <select name="location_id" x-model="formData.location_id" 
                                        class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3">
                                    <option value="">Seleccione ubicación...</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Estatus -->
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Estado Operativo</label>
                                <select name="status" x-model="formData.status" 
                                        class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-10 px-3">
                                    <option value="">-- Seleccione Estado --</option>
                                    <option value="operative">Operativo</option>
                                    <option value="under_maintenance">En Mantenimiento</option>
                                    <option value="out_of_service">Fuera de Servicio</option>
                                </select>
                            </div>
                        </div>

                        <!-- SECCIÓN DINÁMICA: Especificaciones Técnicas -->
                        <div x-show="activeSchema.length > 0" x-transition class="mt-8 p-6 bg-white/5 border border-white/5 rounded-xl">
                            <h3 class="text-sm font-bold text-tecsisa-yellow uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Parámetros Técnicos
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <template x-for="(field, index) in activeSchema" :key="index">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-1" x-text="field.label"></label>
                                        <input :type="field.type === 'number' ? 'number' : 'text'" 
                                               :name="'specs[' + field.label + ']'" 
                                               x-model="formData.specs[field.label]"
                                               class="w-full bg-black/60 border-white/5 rounded-lg text-sm text-white focus:border-tecsisa-yellow focus:ring-0 transition h-9 px-3"
                                               :placeholder="'Valor ' + field.label.toLowerCase()">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="mt-6">
                            <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Notas Técnicas</label>
                            <textarea name="notes" x-model="formData.notes" rows="3" 
                                      class="w-full bg-black/40 border-white/10 rounded-lg text-white focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition p-3 text-sm" 
                                      placeholder="Detalles adicionales..."></textarea>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" @click="showEquipmentModal = false" class="px-6 py-2 rounded-xl text-gray-400 hover:text-white transition font-bold uppercase text-xs">
                                Cancelar
                            </button>

                            <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-2 rounded-xl transition shadow-xl shadow-yellow-400/10">
                                <span x-text="editMode ? 'Actualizar Cambios' : 'Guardar en Catálogo'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
    function inventoryManager(locations, systems) {
        return {
            activeTab: 'equipment',
            allLocations: locations,
            allSystems: systems,
            
            // Modal state
            showEquipmentModal: false,
            editMode: false,
            formAction: '/catalogos/equipment',
            formData: {
                id: '',
                internal_id: '',
                name: '',
                form_factor: '',
                u_height: 1,
                system_id: '',
                location_id: '',
                status: '',
                specs: {},
                notes: ''
            },

            // Systems (Schema) Modal state
            showSystemModal: false,
            systemEditMode: false,
            systemFormAction: '/catalogos/systems',
            systemFormData: {
                id: '',
                name: '',
                form_schema: []
            },

            get activeSchema() {
                if (!this.formData.system_id) return [];
                const sysId = String(this.formData.system_id);
                const found = this.allSystems.find(s => String(s.id) === sysId);
                return found ? (found.form_schema || []) : [];
            },

            openCreateModal() {
                this.editMode = false;
                this.formAction = '/catalogos/equipment';
                this.formData = {
                    id: '',
                    internal_id: '',
                    name: '',
                    form_factor: '',
                    u_height: 1,
                    system_id: '',
                    location_id: '',
                    status: '',
                    specs: {},
                    notes: ''
                };
                this.showEquipmentModal = true;
            },

            openEditModal(eq) {
                console.log("Editando:", eq);
                this.editMode = true;
                this.formAction = `/catalogos/equipment/${eq.id}`;
                
                // Forzamos strings para los selects
                this.formData = {
                    id: eq.id,
                    internal_id: String(eq.internal_id || ''),
                    name: String(eq.name || ''),
                    form_factor: String(eq.form_factor || 'rackmount'),
                    u_height: eq.u_height || 1,
                    system_id: eq.system_id ? String(eq.system_id) : '',
                    location_id: eq.location_id ? String(eq.location_id) : '',
                    status: String(eq.status || 'operative'),
                    specs: eq.specs ? JSON.parse(JSON.stringify(eq.specs)) : {},
                    notes: String(eq.notes || '')
                };
                
                this.showEquipmentModal = true;
            },

            // --- Systems Logic ---
            openCreateSystemModal() {
                this.systemEditMode = false;
                this.systemFormAction = '/catalogos/systems';
                this.systemFormData = {
                    id: '',
                    name: '',
                    form_schema: []
                };
                this.showSystemModal = true;
            },

            openEditSystemModal(sys) {
                this.systemEditMode = true;
                this.systemFormAction = `/catalogos/systems/${sys.id}`;
                this.systemFormData = {
                    id: sys.id,
                    name: sys.name,
                    form_schema: sys.form_schema ? JSON.parse(JSON.stringify(sys.form_schema)) : []
                };
                this.showSystemModal = true;
            },

            addFieldToSchema() {
                this.systemFormData.form_schema.push({ label: '', type: 'text' });
            },

            removeFieldFromSchema(index) {
                this.systemFormData.form_schema.splice(index, 1);
            }
        };
    }
</script>
</x-app-layout>
