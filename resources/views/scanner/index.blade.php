<x-technician-layout>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        #reader video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            border-radius: 2.5rem !important;
        }
        #reader img {
            display: none !important;
        }
        @keyframes scan {
            0% { transform: translateY(0); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(320px); opacity: 0; }
        }
        .animate-scan {
            animation: scan 2.5s ease-in-out infinite;
        }
    </style>
    
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

                <!-- Full Screen Scanner Overlay -->
                <div x-show="isScanning" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="fixed inset-0 z-[100] bg-black flex flex-col items-center justify-center p-4">
                    
                    <!-- Scanner Header (Top) -->
                    <div class="absolute top-0 inset-x-0 p-6 pt-16 sm:pt-10 flex justify-between items-center z-20 bg-gradient-to-b from-black/90 via-black/40 to-transparent">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-tecsisa-yellow rounded-2xl flex items-center justify-center text-black shadow-[0_0_30px_rgba(255,209,0,0.4)] shrink-0">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <span class="block text-sm font-black text-tecsisa-yellow uppercase tracking-[0.3em]">Visión Técnica</span>
                                <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Escaneo QR / Bar / Id</span>
                            </div>
                        </div>
                    </div>

                    <!-- Main Scanner Area (Middle) -->
                    <div class="relative w-full aspect-square max-w-sm rounded-[3rem] overflow-hidden border-2 border-tecsisa-yellow/30 shadow-[0_0_60px_rgba(0,0,0,0.8)] z-10">
                        <div id="reader" class="w-full h-full bg-black"></div>
                        
                        <!-- Scanner Overlay UI -->
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-20">
                            <!-- Corner Frames -->
                            <div class="absolute top-10 left-10 w-12 h-12 border-t-4 border-l-4 border-tecsisa-yellow rounded-tl-lg opacity-80"></div>
                            <div class="absolute top-10 right-10 w-12 h-12 border-t-4 border-r-4 border-tecsisa-yellow rounded-tr-lg opacity-80"></div>
                            <div class="absolute bottom-10 left-10 w-12 h-12 border-b-4 border-l-4 border-tecsisa-yellow rounded-bl-lg opacity-80"></div>
                            <div class="absolute bottom-10 right-10 w-12 h-12 border-b-4 border-r-4 border-tecsisa-yellow rounded-br-lg opacity-80"></div>
                            
                            <!-- Scanning Laser -->
                            <div class="w-full h-1 bg-gradient-to-r from-transparent via-tecsisa-yellow to-transparent shadow-[0_0_20px_rgba(255,209,0,1)] animate-scan opacity-60"></div>
                        </div>
                    </div>

                    <!-- Scanner Controls (Bottom) -->
                    <div class="absolute bottom-0 inset-x-0 p-10 pb-20 flex flex-col gap-6 z-20 bg-gradient-to-t from-black via-black/80 to-transparent">
                        <div class="flex items-center gap-4 max-w-sm mx-auto w-full">
                            <!-- Flash Toggle Button -->
                            <button @click="toggleFlash()" x-show="hasFlash"
                                    class="flex-1 h-16 flex flex-col items-center justify-center bg-white/5 border border-white/10 rounded-[2rem] text-white transition-all backdrop-blur-2xl active:scale-95 group shadow-2xl"
                                    :class="flashOn ? 'bg-tecsisa-yellow/20 border-tecsisa-yellow ring-4 ring-tecsisa-yellow/10' : ''">
                                <svg x-show="!flashOn" class="w-6 h-6 mb-1 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <svg x-show="flashOn" style="display: none;" class="w-6 h-6 mb-1 text-tecsisa-yellow" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span class="text-[9px] font-black uppercase tracking-widest" :class="flashOn ? 'text-tecsisa-yellow' : 'text-gray-500'">Linterna</span>
                            </button>
                            
                            <!-- Close Button -->
                            <button @click="toggleCamera()" class="h-16 w-16 flex items-center justify-center bg-red-500/10 border border-red-500/20 rounded-full text-red-500 transition-all backdrop-blur-2xl active:scale-95 group shadow-2xl">
                                <svg class="w-8 h-8 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <p class="text-center text-[9px] font-bold text-gray-500 uppercase tracking-[0.3em] opacity-40">Identificación de Activos v2.0</p>
                    </div>

                    <!-- Decorative Tech Elements -->
                    <div class="absolute bottom-8 left-8 hidden sm:block">
                        <div class="text-[8px] font-mono text-tecsisa-yellow opacity-30 flex flex-col gap-1 uppercase tracking-widest">
                            <span>Sys.Status: Active</span>
                            <span>Cam.Index: Env-01</span>
                            <span>Buffer: Optimized</span>
                        </div>
                    </div>
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
        const initDiscoveryApp = () => {
            Alpine.data('discoveryApp', (equipments) => ({
                equipments: equipments,
                search: '',
                filterLocation: '',
                filterSystem: '',
                isScanning: false,
                flashOn: false,
                hasFlash: false,
                html5QrCode: null,

                init() {
                    // Prevent memory leaks and camera lock when navigating away via Turbo
                    document.addEventListener('turbo:before-visit', () => {
                        if (this.isScanning && this.html5QrCode) {
                            this.html5QrCode.stop().then(() => {
                                this.isScanning = false;
                                this.flashOn = false;
                            }).catch(() => {});
                        }
                    });
                },

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
                        }).catch(err => {
                            this.isScanning = false;
                        });
                    } else {
                        // Ensure reader is visible before initializing
                        this.isScanning = true;
                        
                        // Small delay to let Alpine render the div
                        setTimeout(() => {
                            if (!this.html5QrCode) this.html5QrCode = new Html5Qrcode("reader");
                            
                            const qrConfig = { 
                                fps: 20, 
                                qrbox: (viewWidth, viewHeight) => {
                                    const minEdge = Math.min(viewWidth, viewHeight);
                                    const size = Math.floor(minEdge * 0.7);
                                    return { width: size, height: size };
                                },
                                aspectRatio: 1.0
                            };

                            this.html5QrCode.start(
                                { facingMode: "environment" }, 
                                qrConfig,
                                (decodedText) => {
                                    if (navigator.vibrate) window.navigator.vibrate(200);
                                    this.html5QrCode.stop().then(() => {
                                        this.isScanning = false;
                                        this.flashOn = false;
                                        this.search = decodedText;
                                    });
                                },
                                (err) => {}
                            ).then(() => {
                                // Check for torch capability
                                try {
                                    const capabilities = this.html5QrCode.getRunningTrackCapabilities();
                                    this.hasFlash = !!capabilities.torch;
                                } catch (e) {
                                    this.hasFlash = false;
                                }
                            }).catch((err) => {
                                console.error(err);
                                this.isScanning = false;
                                alert("Error al acceder a la cámara. Verifique los permisos.");
                            });
                        }, 100);
                    }
                },

                toggleFlash() {
                    if (!this.isScanning) return;
                    
                    this.flashOn = !this.flashOn;
                    
                    try {
                        // Enfoque 1: Intento Directo al Hardware (Mejor para Chrome Android)
                        const videoElement = document.querySelector('#reader video');
                        if (videoElement && videoElement.srcObject) {
                            const track = videoElement.srcObject.getVideoTracks()[0];
                            if (track) {
                                const imageCapture = window.ImageCapture ? new ImageCapture(track) : null;
                                // Verificamos primero si el equipo reporta soporte
                                const capabilities = track.getCapabilities();
                                if (!capabilities.torch) {
                                    throw new Error("El hardware no reporta soporte de linterna (torch).");
                                }

                                track.applyConstraints({
                                    advanced: [{ torch: this.flashOn }]
                                }).then(() => {
                                    console.log("Linterna encendida directo al track.");
                                }).catch(e => {
                                    console.warn("Fallo directo:", e);
                                    // Enfoque 2: Intento a través de la librería HTML5-QRCode
                                    if(this.html5QrCode) {
                                        this.html5QrCode.applyVideoConstraints({
                                            advanced: [{ torch: this.flashOn }]
                                        }).catch(err => {
                                            this.flashOn = false;
                                            alert("La linterna web está bloqueada por su navegador o dispositivo (Común en iPhone/Safari o si la cámara no tiene flash).");
                                        });
                                    }
                                });
                            }
                        }
                    } catch (err) {
                        this.flashOn = false;
                        this.hasFlash = false; // Ocultamos el botón si resulta estar bloqueado para no frustrar.
                        console.error("No soportado:", err);
                    }
                }
            }));
        };

        if (window.Alpine) {
            initDiscoveryApp();
        } else {
            document.addEventListener('alpine:init', initDiscoveryApp);
        }
    </script>
</x-technician-layout>
