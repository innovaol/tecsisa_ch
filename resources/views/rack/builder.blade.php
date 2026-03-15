<x-app-layout>
    <!-- Se personaliza el header incrustado dentro del contexto de Alpine para tener acceso al estado "saving" -->

    <!-- Implementación Drag and Drop con HTML5 API usando Alpine -->
    <div x-data="rackBuilder(@js($unassignedEquipment))" class="flex flex-col min-h-screen lg:h-[calc(100vh-74px)] lg:overflow-hidden">
        
        <!-- Header con el Botón Guardar Topología -->
        <header class="bg-theme-header backdrop-blur-md border-b border-theme shrink-0 transition-colors duration-500 py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap justify-between items-center gap-2">
                <div class="flex items-center gap-2 sm:gap-4 flex-1">
                    <a href="{{ route('catalog.index') }}" class="p-2 text-theme-muted hover:text-tecsisa-yellow transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h2 class="font-bold text-lg sm:text-2xl text-theme tracking-tight flex items-center gap-2">
                        <span class="hidden sm:inline">Distribución de Racks</span>
                        <span class="sm:hidden text-tecsisa-yellow">Racks</span>
                        <div class="group relative inline-block">
                            <svg class="w-5 h-5 text-theme-muted cursor-help p-0.5 hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path></svg>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-64 p-4 bg-black/95 text-[11px] text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-theme shadow-2xl normal-case font-bold backdrop-blur-md">
                                <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-black/95 border-b border-r border-theme rotate-45"></div>
                                Gestiona la ubicación física de los activos en el gabinete EIA-310-D.
                            </div>
                        </div>
                    </h2>
                </div>
                <button @click="saveTopology()" :disabled="saving" class="bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl shadow-xl shadow-tecsisa-yellow/20 transition transform flex justify-center items-center gap-2 whitespace-nowrap active:scale-95" :class="saving ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-0.5'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span class="text-xs uppercase" x-text="saving ? 'Guardando' : 'Guardar'"></span>
                </button>
            </div>
        </header>

        <div class="flex-1 py-4 md:py-6 flex flex-col lg:overflow-hidden min-h-0">
            <div class="max-w-[95rem] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
                
                <!-- CONTROLES Y CATÁLOGO DE EQUIPACIÓN (LEFT PANEL) -->
                <div class="w-full lg:w-96 flex flex-col gap-6 lg:h-full shrink-0">
                <!-- Selector de Gabinete -->
                <div class="bg-theme-card backdrop-blur-md rounded-2xl shadow-xl border border-theme p-5 transition-colors duration-500">
                    <label class="block text-[10px] font-black text-theme-muted uppercase tracking-widest mb-2">Gabinete Activo</label>
                    <select onchange="window.location.search = '?rack_id=' + this.value" class="w-full bg-theme/5 border-theme text-theme rounded-xl focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-4 text-sm font-black uppercase tracking-widest">
                        @foreach($racks as $r)
                            <option value="{{ $r->id }}" {{ $r->id == $rack->id ? 'selected' : '' }}>{{ $r->name }} ({{ $r->total_units }}U)</option>
                        @endforeach
                    </select>
                </div>

                <!-- Equipos Sin Asignar (Catálogo Arrastrable) -->
                <div class="bg-theme-card backdrop-blur-md rounded-2xl shadow-xl border border-theme flex-1 flex flex-col overflow-hidden transition-colors duration-500">
                    <div class="p-4 border-b border-theme bg-theme/5 flex justify-between items-center">
                        <h3 class="text-xs font-black text-theme uppercase tracking-widest">Equipos Libres</h3>
                        <span class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-widest"><span x-text="filteredCount"></span> Items</span>
                    </div>
                    
                    <div class="px-4 py-3 bg-theme/5 border-b border-theme">
                        <div class="relative">
                            <input type="text" x-model="catalogSearch" placeholder="Filtro rápido..." 
                                   class="w-full bg-theme/5 border border-theme rounded-xl text-xs text-theme placeholder-theme-muted focus:ring-2 focus:ring-tecsisa-yellow h-10 pl-10 pr-4 transition-all uppercase font-bold tracking-widest">
                            <div class="absolute left-3.5 top-3 text-theme-muted">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-4 overflow-y-auto flex-1 h-full custom-scrollbar">
                        <div class="space-y-3">
                            <template x-for="eq in unassignedCatalog" :key="eq.id">
                                <div draggable="true" 
                                     x-show="!isPlaced(eq.id) && matchesSearch(eq.internal_id, eq.name)"
                                     x-transition
                                     @dragstart="startDrag($event, eq.id, eq.internal_id, eq.name, eq.u_height, eq.system ? eq.system.name : 'SC')"
                                     @dragend="$event.target.classList.remove('opacity-50')"
                                     @click="selectEquipment(eq.id, eq.internal_id, eq.name, eq.u_height, eq.system ? eq.system.name : 'SC')"
                                     :class="{
                                         'border-l-tecsisa-yellow bg-tecsisa-yellow/10 border-tecsisa-yellow/40 scale-[1.02] shadow-[0_0_15px_rgba(255,209,0,0.3)]': selectedItem && selectedItem.db_id == eq.id, 
                                         'bg-theme/5 hover:bg-theme/10 border-theme': !selectedItem || selectedItem.db_id != eq.id,
                                         'border-l-blue-500 shadow-[0_5px_15px_rgba(59,130,246,0.1)]': getSystemColor(eq.system ? eq.system.name : 'SC') === 'blue' && (selectedItem ? selectedItem.db_id != eq.id : true),
                                         'border-l-red-500 shadow-[0_5px_15px_rgba(239,68,68,0.1)]': getSystemColor(eq.system ? eq.system.name : 'SC') === 'red' && (selectedItem ? selectedItem.db_id != eq.id : true),
                                         'border-l-tecsisa-yellow shadow-[0_5px_15px_rgba(255,209,0,0.1)]': getSystemColor(eq.system ? eq.system.name : 'SC') === 'yellow' && (selectedItem ? selectedItem.db_id != eq.id : true),
                                         'border-l-green-500 shadow-[0_5px_15px_rgba(34,197,94,0.1)]': getSystemColor(eq.system ? eq.system.name : 'SC') === 'green' && (selectedItem ? selectedItem.db_id != eq.id : true)
                                     }"
                                     class="p-3 border rounded-lg border-l-4 cursor-pointer transition-all flex justify-between items-center group">
                                    <div class="flex-1 overflow-hidden">
                                        <div class="flex items-center gap-2 mb-0.5">
                                             <div class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-theme/10 text-tecsisa-yellow border border-theme">
                                                <span x-text="eq.internal_id"></span>
                                            </div>
                                            <template x-if="eq.system">
                                                <span class="text-[8px] uppercase tracking-tighter text-gray-500 font-bold border border-white/5 px-1 rounded" x-text="eq.system.name"></span>
                                            </template>
                                        </div>
                                         <div class="text-xs font-black uppercase tracking-widest whitespace-nowrap overflow-hidden text-ellipsis transition-colors" 
                                              :class="selectedItem && selectedItem.db_id == eq.id ? 'text-theme' : 'text-theme-muted group-hover:text-theme'"
                                              x-text="eq.name"></div>
                                    </div>
                                     <div class="bg-theme/10 text-theme-muted font-bold text-[10px] px-1.5 py-1 rounded border border-theme ml-2 shrink-0">
                                        <span x-text="eq.u_height"></span>U
                                    </div>
                                </div>
                            </template>

                            <div x-show="filteredCount === 0" class="py-8 text-center text-gray-600 text-[10px] uppercase font-bold tracking-widest italic animate-pulse">
                                No hay activos disponibles
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- VISUALIZADOR PIXEL-PERFECT DEL RACK (RIGHT PANEL) -->
            <div class="w-full flex-1 min-h-[500px] sm:min-h-[600px] lg:min-h-0 lg:h-full flex flex-col bg-theme-card backdrop-blur-md rounded-2xl shadow-2xl border border-theme overflow-hidden relative transition-colors duration-500">
                
                <div class="p-4 border-b border-theme bg-theme/5 flex flex-col md:flex-row justify-between items-center gap-4 z-20">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                        <h3 class="text-lg font-black text-theme tracking-widest whitespace-nowrap uppercase">{{ $rack->name }}</h3>
                    </div>
                    
                    <!-- Horizontal Stats Widget -->
                    <div class="flex-1 max-w-xl w-full flex items-center gap-6 px-4 hidden md:flex">
                        <div class="flex-1">
                            <div class="flex justify-between text-[10px] mb-1.5">
                                <span class="text-theme-muted font-black uppercase tracking-widest">Ocupación (<span x-text="occupancyStats.count"></span> equipos)</span>
                                <span class="text-tecsisa-yellow font-black uppercase tracking-widest"><span x-text="occupancyStats.used"></span>/<span x-text="totalU"></span>U (<span x-text="occupancyStats.percent.toFixed(1) + '%'"></span>)</span>
                            </div>
                            <div class="w-full h-1.5 bg-theme/10 rounded-full overflow-hidden border border-theme bg-theme/10 shadow-inner group">
                                <div class="h-full bg-tecsisa-yellow shadow-[0_0_10px_rgba(255,209,0,0.5)] transition-all duration-700 ease-out" :style="'width: ' + occupancyStats.percent + '%'"></div>
                            </div>
                        </div>
                        <div class="text-[10px] text-center hidden lg:block shrink-0 border-l border-theme pl-6">
                            <span class="block text-green-500 font-black text-sm" x-text="totalU - occupancyStats.used + 'U'"></span>
                            <span class="text-theme-muted uppercase font-black tracking-widest">Libres</span>
                        </div>
                    </div>

                    <div class="text-[10px] text-theme-muted bg-theme/5 px-3 py-1.5 rounded font-black border border-theme shrink-0 hidden lg:block uppercase tracking-widest">EIA-310-D</div>
                </div>

                <!-- EL RACK REAL -->
                <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-theme flex justify-center items-start custom-scrollbar relative transition-colors duration-500">
                    
                    <!-- Marco Metálico del Rack -->
                    <div class="relative bg-[#111] border-8 border-[#333] shadow-[inset_0_4px_30px_rgba(0,0,0,1)] flex flex-col w-full max-w-xl pb-2" style="box-shadow: 0 20px 50px rgba(0,0,0,0.8), inset 0 0 20px rgba(0,0,0,1);">
                        
                        <!-- Rieles Izquierdo y Derecho simulados -->
                        <div class="absolute top-0 bottom-0 left-0 w-8 bg-gradient-to-r from-gray-700 to-gray-900 border-r border-black flex flex-col pt-1">
                            @for($i = $rack->total_units; $i >= 1; $i--)
                                <div class="flex-1 flex flex-col justify-around items-center opacity-40">
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                </div>
                            @endfor
                        </div>
                        <div class="absolute top-0 bottom-0 right-0 w-8 bg-gradient-to-l from-gray-700 to-gray-900 border-l border-black flex flex-col pt-1">
                             @for($i = $rack->total_units; $i >= 1; $i--)
                                <div class="flex-1 flex flex-col justify-around items-center opacity-40">
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                    <div class="w-2 h-2 rounded-sm bg-black border border-gray-600"></div>
                                </div>
                            @endfor
                        </div>

                        <!-- Espacio de Montaje (Las unidades) -->
                        <div class="mx-8 flex flex-col bg-black/80 flex-1 relative z-10" id="rack-container">
                            <!-- Generamos las 42 (o X) unidades desde arriba hacia abajo -->
                            <template x-for="unit in rackUnits" :key="unit.number">
                                <div class="relative group border-b border-[#222] h-[28px] flex" 
                                     @dragover.prevent="allowDrop($event, unit)"
                                     @dragleave="leaveDrop($event, unit)"
                                     @drop="drop($event, unit)"
                                     @click="placeEquipment(unit)"
                                     :style="{ zIndex: unit.number }"
                                     :class="{'bg-tecsisa-yellow/10 border-2 border-tecsisa-yellow border-dashed': unit.dragHover && !unit.occupied, 'bg-red-500/10 border-2 border-red-500 border-dashed': unit.dragHover && unit.occupied, 'cursor-pointer hover:bg-white/10 transition': selectedItem && !unit.occupied}">
                                    
                                    <!-- Número de U (Regleta izq) -->
                                    <div class="absolute -left-7 top-0 bottom-0 flex items-center justify-center w-6 text-[10px] font-bold text-gray-400 bg-black/50 pointer-events-none">
                                        U<span x-text="unit.number"></span>
                                    </div>
                                    <!-- Número de U (Regleta der) -->
                                    <div class="absolute -right-7 top-0 bottom-0 flex items-center justify-center w-6 text-[10px] font-bold text-gray-400 bg-black/50 pointer-events-none">
                                        <span x-text="unit.number"></span>
                                    </div>

                                    <!-- Render del Slot: Vacío o Lleno -->
                                    
                                    <!-- Slot Vacío -->
                                    <div x-show="!unit.occupied" class="flex-1 w-full h-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                        <span class="text-xs text-white/30 tracking-widest uppercase font-mono" x-text="selectedItem ? 'Click aquí para instalar' : 'Espacio Libre'"></span>
                                    </div>

                                      <!-- Slot Lleno (Equipo: Renderizado solo en la unidad base) -->
                                     <div x-show="unit.occupied && unit.db_id" class="absolute top-0 left-0 right-0 w-full cursor-pointer group/equip transition-all"
                                          :style="'height: ' + (unit.size * 28) + 'px;'"
                                          draggable="true"
                                         @dragstart="startDrag($event, unit.db_id, unit.eq_id, unit.eq_name, unit.size, unit.system)"
                                         @click.stop="selectEquipment(unit.db_id, unit.eq_id, unit.eq_name, unit.size, unit.system)"
                                         :class="{'ring-2 ring-tecsisa-yellow ring-offset-2 ring-offset-[#0a0f18] z-20': selectedItem && selectedItem.db_id === unit.db_id}">
                                        
                                         <!-- Diseño estilo Switch Realista con alto dinámico -->
                                         <div class="absolute inset-0 bg-[#1e2329] border-y border-gray-600 shadow-[inset_0_1px_0_rgba(255,255,255,0.1),inset_0_-1px_0_rgba(0,0,0,0.5)] flex items-center px-4">
                                            
                                            <!-- Color System Indicator -->
                                            <div class="absolute top-0 bottom-0 left-2 w-1 opacity-60" :class="{
                                                'bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]': getSystemColor(unit.system) === 'blue',
                                                'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]': getSystemColor(unit.system) === 'red',
                                                'bg-tecsisa-yellow shadow-[0_0_10px_rgba(255,209,0,0.5)]': getSystemColor(unit.system) === 'yellow',
                                                'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]': getSystemColor(unit.system) === 'green',
                                                'bg-gray-500': getSystemColor(unit.system) === 'gray'
                                            }"></div>

                                            <!-- Orejas de Rack (Mounting Ears) -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-gray-400 border-r border-black shadow-[inset_1px_0_2px_rgba(255,255,255,0.5)]"></div>
                                            <div class="absolute right-0 top-0 bottom-0 w-2 bg-gray-400 border-l border-black shadow-[inset_-1px_0_2px_rgba(255,255,255,0.5)]"></div>

                                            <!-- Led de Encendido (Verde) -->
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,1)] mr-3 ml-2"></div>

                                            <!-- Marca / ID Corto -->
                                            <div class="text-[10px] font-black text-gray-400 w-24 truncate"><span x-text="unit.eq_id"></span></div>

                                            <!-- Puertos Simulados Visibles (Si es tamaño 1U) -->
                                            <div class="flex-1 flex gap-0.5 justify-end" x-show="unit.size == 1">
                                                <template x-for="i in 12">
                                                    <div class="flex gap-0.5">
                                                        <div class="w-1.5 h-1.5 bg-black border border-gray-700/50 rounded-sm relative mt-1">
                                                            <div class="absolute -top-1.5 left-0 w-0.5 h-0.5 bg-green-500 rounded-full opacity-40"></div>
                                                        </div>
                                                        <div class="w-1.5 h-1.5 bg-black border border-gray-700/50 rounded-sm relative mt-1">
                                                            <div class="absolute -top-1.5 left-0 w-0.5 h-0.5 bg-green-500 rounded-full opacity-40"></div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Nombre grande si es tamaño > 1U (Ej UPS) -->
                                            <div class="flex-1 text-center font-bold text-gray-300 tracking-wider" x-show="unit.size > 1">
                                                <span x-text="unit.eq_name"></span>
                                            </div>

                                        </div>

                                        <!-- Acciones Rápidas del Equipo (z-30 to stay above everything else) -->
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-2 opacity-0 group-hover/equip:opacity-100 transition z-30">
                                            <button @click.prevent.stop="removeEquipment(unit)" class="p-1.5 bg-red-500/20 hover:bg-red-500 text-red-400 hover:text-white rounded transition shadow-lg backdrop-blur-sm" title="Remover del Rack">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>

                                        <!-- Tooltip de Equipo -->
                                        <div class="absolute z-50 left-1/2 -translate-x-1/2 -top-12 bg-black text-white px-3 py-1.5 rounded text-xs opacity-0 group-hover/equip:opacity-100 transition pointer-events-none whitespace-nowrap shadow-xl border border-gray-700">
                                            <span class="text-tecsisa-yellow font-bold" x-text="unit.eq_id"></span> - <span x-text="unit.eq_name"></span>
                                        </div>
                                    </div>
                                    
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    


        </div> <!-- End of Max-w-7xl wrapper where x-data resides! -->
    </div> <!-- Close of main py-6 background -->
    
    <!-- Alpine.js Logic para el Constructor de Rack -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rackBuilder', (unassignedCatalog = []) => ({
                rackId: {{ $rack->id }},
                totalU: {{ $rack->total_units }}, // Total units from PHP ($rack->total_units)
                rackUnits: [],
                existingUnits: @json($rack->units),
                externalTargets: @json($externalTargets ?? []),
                unassignedCatalog: unassignedCatalog,
                saving: false,
                
                // Variable para el equipo seleccionado mediante CLICK (Modo Móvil/Tablet)
                selectedItem: null,
                draggedItem: null, // compatibility for desktop

                catalogSearch: '',

                get occupancyStats() {
                    const used = this.rackUnits.filter(u => u.occupied && u.db_id).reduce((sum, unit) => sum + unit.size, 0);
                    const count = this.rackUnits.filter(u => u.occupied && u.db_id).length;
                    return {
                        used,
                        count,
                        percent: (used / this.totalU) * 100
                    };
                },

                get filteredCount() {
                    return this.unassignedCatalog.filter(eq => 
                        !this.isPlaced(eq.id) && 
                        this.matchesSearch(eq.internal_id, eq.name)
                    ).length;
                },

                matchesSearch(id, name) {
                    if (!this.catalogSearch) return true;
                    const search = String(this.catalogSearch).toLowerCase();
                    const targetId = String(id || '').toLowerCase();
                    const targetName = String(name || '').toLowerCase();
                    return targetId.includes(search) || targetName.includes(search);
                },

                init() {
                    // Inicializar rack vacio de arriba hacia abajo
                    for (let i = this.totalU; i >= 1; i--) {
                        this.rackUnits.push({
                            number: i,
                            occupied: false,
                            size: 1, // Tamaño defenitivo si es un panel ocupado
                            eq_id: null,
                            eq_name: null,
                            db_id: null,
                            system: null,
                            dragHover: false
                        });
                    }

                    // Cargar estado inicial de la Base de Datos
                    if(this.existingUnits && this.existingUnits.length > 0) {
                        this.existingUnits.forEach(exU => {
                            let unitIndex = this.rackUnits.findIndex(u => u.number === exU.unit_number);
                            if(unitIndex !== -1 && exU.equipment) {
                                // Agregamos el equipo que YA está en el rack al catálogo local
                                // para que si el usuario lo quita, aparezca en la lista de la izquierda
                                if (!this.unassignedCatalog.some(e => e.id === exU.equipment.id)) {
                                    this.unassignedCatalog.push({
                                        id: exU.equipment.id,
                                        internal_id: exU.equipment.internal_id,
                                        name: exU.equipment.name,
                                        u_height: exU.equipment.u_height || exU.position_size || 1,
                                        system: exU.equipment.system || null
                                    });
                                }

                                this.rackUnits[unitIndex].occupied = true;
                                this.rackUnits[unitIndex].size = exU.position_size || 1;
                                this.rackUnits[unitIndex].db_id = exU.equipment.id;
                                this.rackUnits[unitIndex].eq_id = exU.equipment.internal_id;
                                this.rackUnits[unitIndex].eq_name = exU.equipment.name;
                                this.rackUnits[unitIndex].system = exU.equipment.system ? exU.equipment.system.name : 'SC';

                                 // Mark sub-slots as occupied without metadata
                                 for(let s = 1; s < exU.position_size; s++) {
                                     if(this.rackUnits[unitIndex + s]) {
                                         this.rackUnits[unitIndex + s].occupied = true;
                                     }
                                 }
                            }
                        });
                    }
                },

                isPlaced(dbId) {
                    // Returns true if the equipment with dbId is currently placed inside the rack
                    return this.rackUnits.some(u => u.occupied && u.db_id == dbId);
                },

                selectEquipment(dbId, displayId, name, size, systemName = null) {
                    // Toggle selection logic
                    if (this.selectedItem && this.selectedItem.db_id === dbId) {
                        this.selectedItem = null;
                        this.draggedItem = null;
                    } else {
                        this.selectedItem = {
                            db_id: dbId,
                            eq_id: displayId,
                            eq_name: name,
                            size: parseInt(size),
                            system: systemName
                        };
                        this.draggedItem = this.selectedItem; // Keep drag compatible with click
                    }
                },

                startDrag(event, dbId, displayId, name, size, systemName = null) {
                    this.selectEquipment(dbId, displayId, name, size, systemName);
                    event.dataTransfer.effectAllowed = 'move';
                    // Pequeña trampita para Alpine
                    setTimeout(() => { event.target.classList.add('opacity-50'); }, 0);
                },

                allowDrop(event, unit) {
                    if(!this.draggedItem) return;

                    // Auto-scroll logic when dragging near top or bottom edges of the scrollable container
                    const container = event.currentTarget.closest('.custom-scrollbar');
                    if (container) {
                        const rect = container.getBoundingClientRect();
                        const offset = 50; // pixels near edge to trigger scroll
                        if (event.clientY - rect.top < offset) {
                            container.scrollTop -= 15;
                        } else if (rect.bottom - event.clientY < offset) {
                            container.scrollTop += 15;
                        }
                    }
                    
                    // Simple check if it fits
                    let fits = true;
                    if(unit.occupied) fits = false;
                    
                    // Checkeamos las U hacia abajo basadas en el array (Recordemos que el array viaja de U45 a U1, asi que hacia abajo son indices sucesivos en el array)
                    const unitIndex = this.rackUnits.findIndex(u => u.number === unit.number);
                    if (unitIndex + this.draggedItem.size > this.totalU) {
                        fits = false; // Se sale por abajo del rack
                    } else {
                        for(let i = 1; i < this.draggedItem.size; i++) {
                            if (this.rackUnits[unitIndex + i].occupied) fits = false;
                        }
                    }

                    if(fits) {
                        unit.dragHover = true;
                    }
                },

                leaveDrop(event, unit) {
                    unit.dragHover = false;
                },

                drop(event, unit) {
                    unit.dragHover = false;
                    if(!this.draggedItem) return;

                    let existingUnit = this.rackUnits.find(u => u.occupied && u.db_id === this.draggedItem.db_id);
                    
                    // Arrays of units currently occupied by this same equipment, so they don't block their own movement
                    let selfIndexes = [];
                    if (existingUnit) {
                        let exIndex = this.rackUnits.findIndex(u => u.number === existingUnit.number);
                        for(let i=0; i<existingUnit.size; i++) {
                            selfIndexes.push(exIndex + i);
                        }
                    }

                    // Revalidamos
                    const unitIndex = this.rackUnits.findIndex(u => u.number === unit.number);
                    let fits = true;
                    
                    if (unitIndex + this.draggedItem.size > this.totalU) fits = false;
                    
                    if(fits) {
                        for(let i = 0; i < this.draggedItem.size; i++) {
                            if (this.rackUnits[unitIndex + i].occupied && !selfIndexes.includes(unitIndex + i)) {
                                fits = false;
                            }
                        }
                    }

                    if(fits) {
                        // Lo quitamos primero de donde estaba (si estaba)
                        if (existingUnit) {
                            this.removeEquipment(existingUnit, false); // No confirmar en movimientos
                        }

                        // ¡Instalar en nueva locación!
                        let targetUnit = this.rackUnits[unitIndex];
                        targetUnit.occupied = true;
                        targetUnit.size = this.draggedItem.size;
                        targetUnit.eq_id = this.draggedItem.eq_id;
                        targetUnit.eq_name = this.draggedItem.eq_name;
                        targetUnit.db_id = this.draggedItem.db_id;
                        targetUnit.system = this.draggedItem.system;

                        // Marcar las unidades inferiores que este equipo "tapa" físicamente para bloqueo
                        for(let i = 1; i < this.draggedItem.size; i++) {
                            this.rackUnits[unitIndex + i].occupied = true;
                        }
                    } else {
                        // Error visual (podríamos poner un Toast)
                        console.log("No cabe aquí, o choca con otro equipo.");
                    }
                    
                    // Restaurar opacidad del dragg (Si tuvieramos ref al DOM del arrastrado)
                    this.draggedItem = null;
                    this.selectedItem = null; // Clear selection after placing
                },

                placeEquipment(unit) {
                    // Logic for clicking to place after selecting
                    if (!this.selectedItem || unit.occupied) return;
                    
                    // Route to drop logic to reuse calculation
                    this.draggedItem = this.selectedItem;
                    this.drop(null, unit);
                },

                removeEquipment(unit, confirmRemoval = true) {
                    if(!unit.occupied || unit.hidden) return; 

                    // Advertencia de integridad simplificada
                    if (confirmRemoval && unit.db_id) {
                        if (!confirm("¿Estás seguro de que deseas retirar el equipo '" + unit.eq_name + "' del rack?")) {
                            return;
                        }
                    }
                    
                    const unitIndex = this.rackUnits.findIndex(u => u.number === unit.number);
                    
                    // Liberar unidades bloqueadas
                    for(let i = 1; i < unit.size; i++) {
                        if(this.rackUnits[unitIndex + i]) {
                            this.rackUnits[unitIndex + i].occupied = false;
                        }
                    }

                    // Limpiar la principal
                    unit.occupied = false;
                    unit.size = 1;
                    unit.eq_id = null;
                    unit.eq_name = null;
                    unit.db_id = null;
                    unit.system = null;
                },

                getSystemColor(name) {
                    if (!name) return 'gray';
                    const n = name.toLowerCase();
                    if (n.includes('red') || n.includes('datos')) return 'blue';
                    if (n.includes('seguridad') || n.includes('cctv')) return 'red';
                    if (n.includes('ups') || n.includes('energía') || n.includes('electrico')) return 'yellow';
                    if (n.includes('voz') || n.includes('video') || n.includes('clinico')) return 'green';
                    return 'gray';
                },

                async saveTopology() {
                    if (this.saving) return;
                    this.saving = true;
                    // Gather all actively placed top-level units in the rack
                    let activeUnits = this.rackUnits.filter(u => u.occupied && !u.hidden && u.db_id);
                    
                    try {
                        let csrfToken = document.head.querySelector('meta[name="csrf-token"]') ? document.head.querySelector('meta[name="csrf-token"]').content : '';
                        
                        let res = await fetch(`/racks/${this.rackId}/save`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ units: activeUnits })
                        });
                        
                        if(res.ok) {
                            let data = await res.json();
                            // Optional: show a pretty notification/toast here if desired
                            console.log("Success", data);
                            window.location.reload();
                        } else {
                            throw new Error('Network error');
                        }
                    } catch(e) {
                        console.error(e);
                        alert('Hubo un error al guardar o perdiste tu sesioón.');
                    }
                    setTimeout(() => { this.saving = false; }, 500);
                }
            }));
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.2); 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1); 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 209, 0, 0.5); 
        }
    </style>
</x-app-layout>
