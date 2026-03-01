<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            {{ __('Distribución de Racks (Pixel-Perfect)') }}
        </h2>
    </x-slot>

    <!-- Implementación Drag and Drop con HTML5 API usando Alpine -->
    <div class="py-6 min-h-[calc(100vh-140px)] md:h-[calc(100vh-140px)]" x-data="rackBuilder()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col md:flex-row gap-6">
            
            <!-- CONTROLES Y CATÁLOGO DE EQUIPACIÓN (LEFT PANEL) -->
            <div class="w-full md:w-1/3 flex flex-col gap-6 h-[400px] md:h-full shrink-0">
                <!-- Selector de Gabinete -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.2)] border border-white/10 p-5">
                    <label class="block text-sm font-medium text-gray-400 uppercase tracking-widest mb-2">Gabinete Activo</label>
                    <select onchange="window.location.search = '?rack_id=' + this.value" class="w-full bg-black/40 border-white/10 text-white rounded-lg focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-sm font-bold">
                        @foreach($racks as $r)
                            <option value="{{ $r->id }}" {{ $r->id == $rack->id ? 'selected' : '' }}>{{ $r->name }} ({{ $r->total_units }}U)</option>
                        @endforeach
                    </select>
                </div>

                <!-- Equipos Sin Asignar (Catálogo Arrastrable) -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-lg border border-white/10 flex-1 flex flex-col overflow-hidden">
                    <div class="p-4 border-b border-white/10 bg-white/5 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Activos en BDR (Sin Enrackar)</h3>
                        <span class="text-xs text-tecsisa-yellow">{{ count($unassignedEquipment) }} Items</span>
                    </div>
                    
                    <div class="p-4 overflow-y-auto flex-1 h-full scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
                        <div class="space-y-3">
                            <!-- Aquí irían los elementos arrastrables (Source objects) -->
                            @foreach($unassignedEquipment as $eq)
                                <div draggable="true" 
                                     @dragstart="startDrag($event, '{{ $eq->id }}', '{{ $eq->internal_id }}', '{{ $eq->name }}', 1)"
                                     @click="selectEquipment('{{ $eq->id }}', '{{ $eq->internal_id }}', '{{ $eq->name }}', 1)"
                                     :class="{'border-l-tecsisa-yellow bg-tecsisa-yellow/10 scale-[1.02] shadow-[0_0_15px_rgba(255,209,0,0.3)]': selectedItem && selectedItem.db_id === '{{ $eq->id }}', 'border-l-blue-500 bg-black/30 hover:bg-white/5': !selectedItem || selectedItem.db_id !== '{{ $eq->id }}'}"
                                     class="p-3 border border-white/5 rounded-lg border-l-4 cursor-pointer transition-all flex justify-between items-center group">
                                    <div>
                                        <div class="text-xs font-mono font-bold" :class="{'text-tecsisa-yellow': selectedItem && selectedItem.db_id === '{{ $eq->id }}', 'text-tecsisa-yellow': !selectedItem || selectedItem.db_id !== '{{ $eq->id }}'}">{{ $eq->internal_id }}</div>
                                        <div class="text-sm font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[180px]" :class="{'text-white': selectedItem && selectedItem.db_id === '{{ $eq->id }}', 'text-gray-300': !selectedItem || selectedItem.db_id !== '{{ $eq->id }}'}">{{ $eq->name }}</div>
                                    </div>
                                    <div class="bg-white/10 text-gray-400 text-xs px-2 py-1 rounded">1U</div>
                                </div>
                            @endforeach
                            
                             <!-- Ejemplos extras para llenar -->
                             <div draggable="true" 
                                     @dragstart="startDrag($event, 'PP-01', 'PTC-FO-01', 'Bandeja ODF 24 Hilos', 1)"
                                     @click="selectEquipment('PP-01', 'PTC-FO-01', 'Bandeja ODF 24 Hilos', 1)"
                                     :class="{'border-l-tecsisa-yellow bg-tecsisa-yellow/10 scale-[1.02] shadow-[0_0_15px_rgba(255,209,0,0.3)]': selectedItem && selectedItem.db_id === 'PP-01', 'border-l-orange-500 bg-black/30 hover:bg-white/5': !selectedItem || selectedItem.db_id !== 'PP-01'}"
                                     class="p-3 border border-white/5 rounded-lg border-l-4 cursor-pointer transition-all flex justify-between items-center group">
                                    <div>
                                        <div class="text-xs font-mono font-bold" :class="{'text-tecsisa-yellow': selectedItem && selectedItem.db_id === 'PP-01', 'text-orange-400': !selectedItem || selectedItem.db_id !== 'PP-01'}">PTC-FO-01</div>
                                        <div class="text-sm font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[180px]" :class="{'text-white': selectedItem && selectedItem.db_id === 'PP-01', 'text-gray-300': !selectedItem || selectedItem.db_id !== 'PP-01'}">Bandeja ODF 24 Hilos</div>
                                    </div>
                                    <div class="bg-white/10 text-gray-400 text-xs px-2 py-1 rounded">1U</div>
                                </div>
                                <div draggable="true" 
                                     @dragstart="startDrag($event, 'UPS-01', 'UPS-MDF-01', 'APC Smart-UPS 3000VA', 2)"
                                     @click="selectEquipment('UPS-01', 'UPS-MDF-01', 'APC Smart-UPS 3000VA', 2)"
                                     :class="{'border-l-tecsisa-yellow bg-tecsisa-yellow/10 scale-[1.02] shadow-[0_0_15px_rgba(255,209,0,0.3)]': selectedItem && selectedItem.db_id === 'UPS-01', 'border-l-red-500 bg-black/30 hover:bg-white/5': !selectedItem || selectedItem.db_id !== 'UPS-01'}"
                                     class="p-3 border border-white/5 rounded-lg border-l-4 cursor-pointer transition-all flex justify-between items-center group">
                                    <div>
                                        <div class="text-xs font-mono font-bold" :class="{'text-tecsisa-yellow': selectedItem && selectedItem.db_id === 'UPS-01', 'text-red-400': !selectedItem || selectedItem.db_id !== 'UPS-01'}">UPS-MDF-01</div>
                                        <div class="text-sm font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[180px]" :class="{'text-white': selectedItem && selectedItem.db_id === 'UPS-01', 'text-gray-300': !selectedItem || selectedItem.db_id !== 'UPS-01'}">APC Smart-UPS 3000VA</div>
                                    </div>
                                    <div class="bg-tecsisa-yellow/20 text-tecsisa-yellow text-xs px-2 py-1 rounded border border-tecsisa-yellow/30">2U</div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISUALIZADOR PIXEL-PERFECT DEL RACK (RIGHT PANEL) -->
            <div class="w-full md:w-2/3 h-[600px] md:h-full flex flex-col bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-[0_4px_40px_rgba(0,0,0,0.4)] border border-white/10 overflow-hidden relative">
                
                <div class="p-4 border-b border-white/10 bg-black/40 flex justify-between items-center" style="box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.5)]"></div>
                        <h3 class="text-lg font-bold text-white tracking-widest">{{ $rack->name }}</h3>
                    </div>
                    <div class="text-xs text-gray-400 bg-white/5 px-2 py-1 rounded font-mono border border-white/10">Estándar EIA-310-D (19")</div>
                </div>

                <!-- EL RACK REAL -->
                <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#0a0f18] flex justify-center items-start custom-scrollbar relative">
                    
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
                                <div class="relative group border-b border-[#222] min-h-[28px] flex" 
                                     :style="unit.occupied ? 'height: ' + (28 * unit.size) + 'px;' : 'height: 28px;'"
                                     x-show="!unit.hidden"
                                     @dragover.prevent="allowDrop($event, unit)"
                                     @dragleave="leaveDrop($event, unit)"
                                     @drop="drop($event, unit)"
                                     @click="placeEquipment(unit)"
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

                                    <!-- Slot Lleno (Equipo) -->
                                    <div x-show="unit.occupied" class="flex-1 w-full h-full relative cursor-pointer group/equip"
                                         @dblclick="removeEquipment(unit)">
                                        
                                        <!-- Diseño estilo Switch Realista -->
                                        <div class="absolute inset-0 bg-[#1e2329] border-y border-gray-600 shadow-[inset_0_1px_0_rgba(255,255,255,0.1),inset_0_-1px_0_rgba(0,0,0,0.5)] flex items-center px-4 overflow-hidden">
                                            
                                            <!-- Orejas de Rack (Mounting Ears) -->
                                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-gray-400 border-r border-black shadow-[inset_1px_0_2px_rgba(255,255,255,0.5)]"></div>
                                            <div class="absolute right-0 top-0 bottom-0 w-2 bg-gray-400 border-l border-black shadow-[inset_-1px_0_2px_rgba(255,255,255,0.5)]"></div>

                                            <!-- Led de Encendido (Verde) -->
                                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,1)] mr-3"></div>

                                            <!-- Marca / ID Corto -->
                                            <div class="text-[10px] font-black text-gray-400 w-24 truncate"><span x-text="unit.eq_id"></span></div>

                                            <!-- Puertos Simulados Visibles (Si es tamaño 1U) -->
                                            <div class="flex-1 flex gap-0.5 justify-end" x-show="unit.size == 1">
                                                <template x-for="i in 24">
                                                    <div class="w-2.5 h-3 bg-black border border-gray-700/50 rounded-sm relative">
                                                        <div class="absolute top-0 right-0 w-0.5 h-0.5 bg-green-500 rounded-full"></div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Nombre grande si es tamaño > 1U (Ej UPS) -->
                                            <div class="flex-1 text-center font-bold text-gray-300 tracking-wider" x-show="unit.size > 1">
                                                <span x-text="unit.eq_name"></span>
                                            </div>

                                        </div>

                                        <!-- Tooltip de Equipo -->
                                        <div class="absolute z-50 left-1/2 -translate-x-1/2 -top-12 bg-black text-white px-3 py-1.5 rounded text-xs opacity-0 group-hover/equip:opacity-100 transition pointer-events-none whitespace-nowrap shadow-xl border border-gray-700">
                                            <span class="text-tecsisa-yellow font-bold" x-text="unit.eq_id"></span> - <span x-text="unit.eq_name"></span>
                                            <div class="text-[9px] text-gray-400 mt-0.5" x-text="'Doble-Click para desencrackar (' + unit.size + 'U)'"></div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Button Panel -->
                <div class="p-4 bg-black/60 border-t border-white/10 flex justify-end shrink-0" style="box-shadow: 0 -4px 10px rgba(0,0,0,0.5);">
                    <button @click="saveTopology()" :disabled="saving" class="w-full md:w-auto bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black px-8 py-3 rounded-xl shadow-[0_5px_15px_rgba(255,209,0,0.3)] transition transform flex justify-center items-center gap-2" :class="saving ? 'opacity-70 cursor-not-allowed' : 'hover:-translate-y-0.5'">
                        <svg x-show="!saving" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-5 w-5 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span x-text="saving ? 'Guardando Topología...' : 'Guardar Topología'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Alpine.js Logic para el Constructor de Rack -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('rackBuilder', () => ({
                rackId: {{ $rack->id }},
                totalU: {{ $rack->total_units }}, // Total units from PHP ($rack->total_units)
                rackUnits: [],
                existingUnits: @json($rack->units),
                saving: false,
                
                // Variable para el equipo seleccionado mediante CLICK (Modo Móvil/Tablet)
                selectedItem: null,
                draggedItem: null, // compatibility for desktop

                init() {
                    // Inicializar rack vacio de arriba hacia abajo
                    for (let i = this.totalU; i >= 1; i--) {
                        this.rackUnits.push({
                            number: i,
                            occupied: false,
                            size: 1, // Tamaño defenitivo si es un panel ocupado
                            hidden: false, // Se usa si un equipo de 2U tapa la U de abajo
                            eq_id: null,
                            eq_name: null,
                            db_id: null,
                            dragHover: false
                        });
                    }

                    // Cargar estado inicial de la Base de Datos
                    if(this.existingUnits && this.existingUnits.length > 0) {
                        this.existingUnits.forEach(exU => {
                            let unitIndex = this.rackUnits.findIndex(u => u.number === exU.unit_number);
                            if(unitIndex !== -1 && exU.equipment) {
                                this.rackUnits[unitIndex].occupied = true;
                                this.rackUnits[unitIndex].size = exU.position_size || 1;
                                this.rackUnits[unitIndex].db_id = exU.equipment.id;
                                this.rackUnits[unitIndex].eq_id = exU.equipment.internal_id;
                                this.rackUnits[unitIndex].eq_name = exU.equipment.name;

                                // Hide slots below the top slot based on size (simulating rack constraints)
                                for(let s = 1; s < exU.position_size; s++) {
                                    if(this.rackUnits[unitIndex + s]) {
                                        this.rackUnits[unitIndex + s].hidden = true;
                                        this.rackUnits[unitIndex + s].occupied = true;
                                    }
                                }
                            }
                        });
                    }
                },

                selectEquipment(dbId, displayId, name, size) {
                    // Toggle selection logic
                    if (this.selectedItem && this.selectedItem.db_id === dbId) {
                        this.selectedItem = null;
                        this.draggedItem = null;
                    } else {
                        this.selectedItem = {
                            db_id: dbId,
                            eq_id: displayId,
                            eq_name: name,
                            size: parseInt(size)
                        };
                        this.draggedItem = this.selectedItem; // Keep drag compatible with click
                    }
                },

                startDrag(event, dbId, displayId, name, size) {
                    this.selectEquipment(dbId, displayId, name, size);
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

                    // Evitar duplicados: Si el equipo ya está en el rack, lo movemos (desinstalamos del anterior)
                    let existingUnit = this.rackUnits.find(u => u.occupied && u.db_id === this.draggedItem.db_id);
                    if (existingUnit) {
                        this.removeEquipment(existingUnit);
                    }

                    // Revalidamos
                    const unitIndex = this.rackUnits.findIndex(u => u.number === unit.number);
                    let fits = true;
                    if(unit.occupied) fits = false;
                    if (unitIndex + this.draggedItem.size > this.totalU) fits = false;
                    for(let i = 1; i < this.draggedItem.size; i++) {
                        if (this.rackUnits[unitIndex + i].occupied) fits = false;
                    }

                    if(fits) {
                        // ¡Instalar!
                        unit.occupied = true;
                        unit.size = this.draggedItem.size;
                        unit.eq_id = this.draggedItem.eq_id;
                        unit.eq_name = this.draggedItem.eq_name;
                        unit.db_id = this.draggedItem.db_id;

                        // Esconder las unidades inferiores que este equipo "tapa" fisicamente
                        for(let i = 1; i < this.draggedItem.size; i++) {
                            this.rackUnits[unitIndex + i].hidden = true;
                            this.rackUnits[unitIndex + i].occupied = true; // Para bloquear futuras caidas
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

                removeEquipment(unit) {
                    if(!unit.occupied || unit.hidden) return; // Only top unit is interactive
                    
                    const unitIndex = this.rackUnits.findIndex(u => u.number === unit.number);
                    
                    // Restaurar unidades ocultadas
                    for(let i = 1; i < unit.size; i++) {
                        if(this.rackUnits[unitIndex + i]) {
                            this.rackUnits[unitIndex + i].hidden = false;
                            this.rackUnits[unitIndex + i].occupied = false;
                        }
                    }

                    // Limpiar la principal
                    unit.occupied = false;
                    unit.size = 1;
                    unit.eq_id = null;
                    unit.eq_name = null;
                    unit.db_id = null;
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
