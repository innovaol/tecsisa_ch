<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            {{ __('Distribución de Racks (Pixel-Perfect)') }}
        </h2>
    </x-slot>

    <!-- Implementación Drag and Drop con HTML5 API usando Alpine -->
    <div class="py-6 min-h-[calc(100vh-140px)] md:h-[calc(100vh-140px)]">
        <div x-data="rackBuilder()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col md:flex-row gap-6">
            
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
                                     x-show="!isPlaced('{{ $eq->id }}')"
                                     x-transition
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
                                     x-show="!isPlaced('PP-01')"
                                     x-transition
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
                                     x-show="!isPlaced('UPS-01')"
                                     x-transition
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
                                    <div x-show="unit.occupied" class="flex-1 w-full h-full relative cursor-pointer group/equip transition-all"
                                         draggable="true"
                                         @dragstart="startDrag($event, unit.db_id, unit.eq_id, unit.eq_name, unit.size)"
                                         @click.stop="selectEquipment(unit.db_id, unit.eq_id, unit.eq_name, unit.size)"
                                         :class="{'ring-2 ring-tecsisa-yellow ring-offset-2 ring-offset-[#0a0f18] z-20': selectedItem && selectedItem.db_id === unit.db_id}">
                                        
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

                                        <!-- Acciones Rápidas del Equipo (z-30 to stay above everything else) -->
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-2 opacity-0 group-hover/equip:opacity-100 transition z-30">
                                            <button @click.prevent.stop="openPortViewer(unit)" class="p-1.5 bg-blue-500/20 hover:bg-blue-500 text-blue-400 hover:text-white rounded transition shadow-lg backdrop-blur-sm" title="Inspeccionar Puertos">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
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
    
    <!-- Modal: Visor de Puertos (Glassmorphism) -->
    <div x-show="showPortModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
         style="display: none;"
         :style="{ display: showPortModal ? 'flex' : 'none' }">
        
        <div class="bg-tecsisa-dark border border-white/10 rounded-2xl w-full max-w-5xl mx-4 overflow-hidden shadow-[0_0_50px_rgba(0,0,0,1)] relative"
             @click.away="closePortViewer()">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-white/5 flex justify-between items-center bg-gradient-to-r from-blue-900/20 to-transparent">
                <div>
                    <h3 class="text-xl font-black text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                        Panel de Puertos
                    </h3>
                    <div class="text-sm text-gray-400 mt-1" x-show="inspectingEquipment">
                        Equipo: <span class="text-tecsisa-yellow font-mono font-bold" x-text="inspectingEquipment?.internal_id"></span> 
                        - <span x-text="inspectingEquipment?.name"></span>
                    </div>
                </div>
                <button @click="closePortViewer()" class="text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 p-2 rounded-full transition relative group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body: Puertos Simulados -->
            <div class="p-8 min-h-[200px] flex flex-col justify-center">
                <div x-show="!loadingPorts" class="grid grid-cols-12 gap-3" x-transition>
                    <template x-for="port in equipmentPorts" :key="port.id">
                        <div class="col-span-1 aspect-square bg-gradient-to-b from-[#1a1f26] to-[#0f1217] border border-gray-700 rounded shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] relative group/port cursor-pointer transition transform hover:scale-110 flex flex-col items-center justify-center"
                             :class="{
                                'border-green-500/50 shadow-[0_0_10px_rgba(34,197,94,0.3)]': port.status === 'connected',
                                'border-red-500/50 shadow-[0_0_10px_rgba(239,68,68,0.3)]': port.status === 'broken'
                             }">
                            <div class="text-[9px] font-mono text-gray-500 absolute top-0.5" x-text="port.label"></div>
                            
                            <!-- RJ45 Graphic (simplified) -->
                            <div class="w-4 h-4 bg-black border border-gray-800 rounded-sm mt-2 relative overflow-hidden" x-show="port.type == 'rj45'">
                                <div class="absolute top-0 right-1 w-0.5 h-full" :class="port.status === 'connected' ? 'bg-green-500/80 animate-pulse' : 'bg-gray-800'"></div>
                            </div>

                            <!-- SFP Graphic -->
                            <div class="w-5 h-3 bg-black border border-gray-800 rounded-sm mt-2 flex items-center justify-center" x-show="port.type == 'sfp' || port.type == 'sfp_plus'">
                                <div class="w-2 h-1 bg-gray-600 rounded-sm"></div>
                            </div>
                            
                            <!-- Status Indicator -->
                            <div class="absolute bottom-1 right-1 w-2 h-2 rounded-full border border-black" 
                                 :class="{
                                    'bg-green-500 shadow-[0_0_5px_rgba(34,197,94,1)]': port.status === 'connected',
                                    'bg-red-500 shadow-[0_0_5px_rgba(239,68,68,1)]': port.status === 'broken',
                                    'bg-gray-600': port.status === 'free'
                                 }"></div>

                            <!-- Hover Tooltip Port -->
                            <div class="absolute z-50 left-1/2 -translate-x-1/2 -top-10 bg-black text-white px-2 py-1 rounded text-xs opacity-0 group-hover/port:opacity-100 transition pointer-events-none whitespace-nowrap shadow-xl border border-gray-700">
                                Puerto <span class="text-white font-bold" x-text="port.label"></span> (<span x-text="port.type"></span>)
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Loading / Error States -->
                <div x-show="loadingPorts" class="w-full h-32 flex flex-col items-center justify-center text-gray-400 gap-4">
                    <svg class="animate-spin h-8 w-8 text-tecsisa-yellow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Conectando e inspeccionando interfaz remota...</span>
                </div>

                <div x-show="portError" class="w-full h-32 flex flex-col items-center justify-center text-red-400 gap-2">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="font-bold">Error de Conexión</span>
                    <span class="text-sm">El equipo no respondió a la consulta física de puertos. Es posible que sea un equipo simulado (no guardado) o un error de red.</span>
                </div>
            </div>
            
            <div class="px-6 py-4 bg-black/40 border-t border-white/5 flex justify-end">
                <button @click="closePortViewer()" class="px-5 py-2 text-sm font-bold text-gray-300 hover:text-white transition">Cerrar</button>
            </div>
        </div>
    </div>

        </div> <!-- End of Max-w-7xl wrapper where x-data resides! -->
    </div> <!-- Close of main py-6 background -->
    
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

                // Port Viewer variables
                showPortModal: false,
                loadingPorts: false,
                portError: false,
                inspectingEquipment: null,
                equipmentPorts: [],

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

                isPlaced(dbId) {
                    // Returns true if the equipment with dbId is currently placed inside the rack
                    return this.rackUnits.some(u => u.occupied && u.db_id == dbId);
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
                            this.removeEquipment(existingUnit);
                        }

                        // ¡Instalar en nueva locación!
                        let targetUnit = this.rackUnits[unitIndex];
                        targetUnit.occupied = true;
                        targetUnit.size = this.draggedItem.size;
                        targetUnit.eq_id = this.draggedItem.eq_id;
                        targetUnit.eq_name = this.draggedItem.eq_name;
                        targetUnit.db_id = this.draggedItem.db_id;

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

                // ---- PORT VIEWER ACTIONS ----
                async openPortViewer(unit) {
                    if (!unit.occupied || !unit.db_id) {
                        alert("Este equipo aún no existe en el registro central o es un marcador temporal. Por favor, guarda el rack primero.");
                        return;
                    }
                    
                    this.showPortModal = true;
                    this.loadingPorts = true;
                    this.portError = false;
                    this.inspectingEquipment = null;
                    this.equipmentPorts = [];

                    try {
                        let res = await fetch(`/api/equipment/${unit.db_id}/ports`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (res.ok) {
                            let data = await res.json();
                            this.inspectingEquipment = data.equipment;
                            this.equipmentPorts = data.ports;
                        } else {
                            throw new Error('API Error: ' + res.status);
                        }
                    } catch (e) {
                        console.error("No se pudieron cargar puertos:", e);
                        this.portError = true;
                    }

                    this.loadingPorts = false;
                },

                closePortViewer() {
                    this.showPortModal = false;
                    setTimeout(() => {
                        this.equipmentPorts = [];
                        this.inspectingEquipment = null;
                    }, 300);
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
