<x-technician-layout>
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <div class="py-12 px-4 md:py-16 max-w-7xl mx-auto" x-data="discoveryApp(@js($equipments))">
        <!-- Header Section -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-8 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Centro de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Descubrimiento</span>
                    </h2>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Localización avanzada de infraestructura en sitio</p>
                </div>
                
                <!-- Scanner Trigger Button -->
                <!-- Scanner Trigger Button (Visible only on Desktop in header) -->
                <button @click="toggleCamera()" class="hidden sm:flex items-center justify-center gap-3 bg-white/5 border border-theme hover:border-tecsisa-yellow p-4 rounded-2xl active:scale-95 transition-all group">
                    <div class="w-10 h-10 bg-tecsisa-yellow rounded-xl flex items-center justify-center text-black shadow-lg shadow-yellow-400/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div class="text-left">
                        <span class="block text-[10px] font-black text-tecsisa-yellow uppercase tracking-widest mb-0.5">Visión QR</span>
                        <span class="block text-[8px] font-bold text-gray-500 uppercase">Activar Escáner</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- SEARCH & FILTERS PANEL (4/12) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Search Input -->
                <div class="bg-theme-card border border-theme rounded-[2rem] p-6 shadow-2xl relative overflow-hidden transition-colors duration-500">
                    <div class="flex justify-between items-center mb-4 ml-1">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Búsqueda Técnica</label>
                        <!-- Mobile Scanner Button (Visible only on small screens) -->
                        <button @click="toggleCamera()" type="button" 
                                class="sm:hidden flex items-center justify-center p-2.5 bg-tecsisa-yellow text-black rounded-xl shadow-lg active:scale-90 transition-transform"
                                :class="isScanning ? 'animate-pulse bg-red-500 text-white' : ''">
                            <svg x-show="!isScanning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            <svg x-show="isScanning" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="relative group text-right">
                        <input type="text" x-model="search" placeholder="ID, Nombre o Serial..." 
                               class="w-full bg-black/10 border border-theme rounded-2xl pl-5 pr-12 py-5 focus:ring-1 focus:ring-tecsisa-yellow text-md tracking-wider font-black shadow-inner uppercase transition-all duration-300 outline-none" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1">
                            <!-- Magnifying Glass -->
                            <div class="w-6 h-6 flex items-center justify-center text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scanner Overlay logic moved to top or mid for better focus -->
                <div x-show="isScanning" x-transition class="bg-black rounded-[2rem] overflow-hidden relative border-2 border-tecsisa-yellow/50 shadow-2xl aspect-square mb-6">
                    <div id="reader" class="w-full h-full"></div>
                    <!-- Scanner Laser Animation -->
                    <div class="absolute top-0 inset-x-0 h-1 bg-tecsisa-yellow/50 shadow-[0_0_15px_rgba(255,209,0,0.8)] animate-scan z-10"></div>
                </div>

                <!-- Filters -->
                <div class="bg-theme-card border border-theme rounded-[2rem] p-6 shadow-2xl transition-colors duration-500 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 ml-1">Filtrar por Ubicación</label>
                        <select x-model="filterLocation" class="w-full bg-black/10 border border-theme rounded-xl px-4 py-4 text-xs font-bold text-theme outline-none focus:border-tecsisa-yellow transition">
                            <option value="">Todas las Ubicaciones</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 ml-1">Filtrar por Sistema</label>
                        <select x-model="filterSystem" class="w-full bg-black/10 border border-theme rounded-xl px-4 py-4 text-xs font-bold text-theme outline-none focus:border-tecsisa-yellow transition">
                            <option value="">Todos los Sistemas</option>
                            @foreach($systems as $sys)
                                <option value="{{ $sys->id }}">{{ $sys->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button @click="resetFilters()" x-show="search || filterLocation || filterSystem" class="w-full py-3 text-[9px] font-black text-tecsisa-yellow uppercase tracking-[0.2em] hover:bg-white/5 rounded-xl transition">
                        Limpiar
                    </button>
                </div>

            <!-- RESULTS LIST (8/12) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="flex items-center justify-between px-4">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-gray-500">Resultados Encontrados (<span x-text="filteredEquipments.length"></span>)</h3>
                    <div class="h-px bg-theme border-t border-theme flex-1 ml-6"></div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <template x-for="eq in filteredEquipments" :key="eq.id">
                        <a :href="'{{ route('technician.scanner.result', '') }}/' + eq.id" 
                           class="bg-theme-card border border-theme p-5 rounded-[1.5rem] flex items-center justify-between group hover:border-tecsisa-yellow/30 transition-all duration-300 shadow-lg">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-black/10 rounded-2xl flex items-center justify-center border border-theme text-tecsisa-yellow group-hover:bg-tecsisa-yellow group-hover:text-black transition-all">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-[10px] font-black text-tecsisa-yellow font-mono tracking-widest" x-text="eq.internal_id"></span>
                                        <span class="text-[8px] bg-theme-border px-2 py-0.5 rounded text-gray-500 font-bold uppercase" x-text="eq.system ? eq.system.name : 'GENÉRICO'"></span>
                                    </div>
                                    <h4 class="text-sm font-black transition-colors" :class="theme === 'light' ? 'text-slate-800' : 'text-white'" x-text="eq.name"></h4>
                                    <p class="text-[9px] text-gray-500 uppercase font-black tracking-widest mt-1 flex items-center gap-2">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                        <span x-text="eq.location ? eq.location.name : 'Sin ubicación'"></span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" :class="eq.status === 'operative' ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.3)]' : 'bg-red-500'"></div>
                                <svg class="w-5 h-5 text-gray-700 group-hover:text-tecsisa-yellow group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>
                    </template>

                    <!-- Empty State -->
                    <div x-show="filteredEquipments.length === 0" class="py-20 text-center bg-black/5 rounded-[2rem] border border-dashed border-theme">
                        <svg class="w-12 h-12 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h5 class="text-xs font-black uppercase tracking-widest text-gray-400">Sin coincidencias técnicas</h5>
                        <p class="text-[9px] text-gray-500 uppercase mt-2">Prueba ajustando los filtros o buscando por Serial</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('discoveryApp', (equipments) => ({
                equipments: equipments,
                search: '',
                filterLocation: '',
                filterSystem: '',
                isScanning: false,
                html5QrCode: null,

                resetFilters() {
                    this.search = '';
                    this.filterLocation = '';
                    this.filterSystem = '';
                },

                get filteredEquipments() {
                    return this.equipments.filter(eq => {
                        const matchesSearch = !this.search || 
                            eq.name.toLowerCase().includes(this.search.toLowerCase()) ||
                            eq.internal_id.toLowerCase().includes(this.search.toLowerCase()) ||
                            (eq.serial_number && eq.serial_number.toLowerCase().includes(this.search.toLowerCase()));
                        
                        const matchesLocation = !this.filterLocation || eq.location_id == this.filterLocation;
                        const matchesSystem = !this.filterSystem || eq.system_id == this.filterSystem;

                        return matchesSearch && matchesLocation && matchesSystem;
                    });
                },

                toggleCamera() {
                    if (this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        });
                    } else {
                        if (!this.html5QrCode) this.html5QrCode = new Html5Qrcode("reader");
                        
                        let config = { fps: 10, qrbox: { width: 250, height: 250 } };
                        this.html5QrCode.start(
                            { facingMode: "environment" }, 
                            config,
                            (decodedText) => {
                                if (navigator.vibrate) window.navigator.vibrate(200);
                                this.html5QrCode.stop().then(() => {
                                    this.isScanning = false;
                                    this.search = decodedText;
                                });
                            },
                            (err) => {})
                        .then(() => {
                            this.isScanning = true;
                        })
                        .catch((err) => {
                            alert("No se pudo iniciar la cámara.");
                        });
                    }
                }
            }));
        });
    </script>
</x-technician-layout>
