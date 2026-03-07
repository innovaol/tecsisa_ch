<x-technician-layout>
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <div class="py-8 px-5 md:py-12 max-w-4xl mx-auto" x-data="scannerApp()">
        <!-- Header Section: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-8 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div>
                    <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Identificación de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Activos</span>
                    </h2>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Escaneo de radiofrecuencia o ingreso manual de seriales</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            <!-- CAMERA SCANNER VIEW -->
            <div x-show="activeTab === 'scan'" x-transition class="flex flex-col items-center">
                <div class="w-full aspect-square bg-black rounded-[2.5rem] overflow-hidden relative border-2 border-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.8)] mb-8 transition-all duration-500" :class="theme === 'light' ? 'border-slate-200' : 'border-white/10'">
                    <div class="absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
                        <div class="w-48 h-48 border-2 border-tecsisa-yellow/30 rounded-3xl relative">
                            <div class="absolute -top-1 -left-1 w-8 h-8 border-t-4 border-l-4 border-tecsisa-yellow"></div>
                            <div class="absolute -top-1 -right-1 w-8 h-8 border-t-4 border-r-4 border-tecsisa-yellow"></div>
                            <div class="absolute -bottom-1 -left-1 w-8 h-8 border-b-4 border-l-4 border-tecsisa-yellow"></div>
                            <div class="absolute -bottom-1 -right-1 w-8 h-8 border-b-4 border-r-4 border-tecsisa-yellow"></div>
                            <div class="absolute w-full h-[2px] bg-red-500 shadow-[0_0_20px_rgba(239,68,68,1)]" :class="laserAnim"></div>
                        </div>
                    </div>
                    <div id="reader" class="w-full h-full object-cover grayscale opacity-80 contrast-125"></div>
                </div>
                <h3 class="font-black uppercase text-xs tracking-[0.2em] mb-3 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Apunta al Código QR</h3>
                <p class="text-[9px] text-gray-500 text-center max-w-[250px] uppercase font-bold tracking-widest leading-relaxed">
                    Asegúrate de tener buena iluminación para detectar la placa serial.
                </p>
                <button @click="toggleCamera()" class="mt-8 px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest transition shadow-2xl flex items-center gap-3 active:scale-95" :class="isScanning ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-tecsisa-yellow text-black'">
                    <span x-text="isScanning ? 'Detener Escaneo' : 'Iniciar Cámara'"></span>
                </button>
            </div>

            <!-- MANUAL SEARCH VIEW -->
            <div class="flex flex-col">
                <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden text-center mb-8 transition-all duration-500">
                    <div class="w-16 h-16 bg-black/10 border border-theme rounded-[1.5rem] flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-8 h-8 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h2 class="text-lg font-black uppercase tracking-widest mb-2 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Carga Manual</h2>
                    <p class="text-[9px] text-gray-500 uppercase tracking-[0.2em] font-bold">Ingresa el ID del Activo</p>
                </div>

                <form action="{{ route('technician.scanner.search') }}" method="POST" id="searchForm">
                    @csrf
                    <input type="text" name="query" x-model="query" id="searchInput" placeholder="Ej. CAM-EXT-04" 
                           class="w-full bg-black/10 border border-theme rounded-3xl px-6 py-6 focus:ring-0 focus:border-tecsisa-yellow text-2xl tracking-[0.3em] font-black shadow-inner uppercase transition-all duration-300 transition-colors" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                    
                    <button type="submit" :disabled="query.length < 3" 
                            class="mt-6 w-full py-5 rounded-3xl font-black uppercase tracking-[0.2em] transition flex items-center justify-center gap-3 text-xs"
                            :class="{'opacity-30 cursor-not-allowed bg-white/5 text-gray-500 border border-white/5': query.length < 3, 'bg-tecsisa-yellow hover:bg-yellow-400 text-black shadow-[0_15px_40px_rgba(255,209,0,0.4)] transition-all active:scale-95': query.length >= 3}">
                        Validar Activo
                        <svg x-show="query.length >= 3" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scanner Script Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerApp', () => ({
                activeTab: new URLSearchParams(window.location.search).get('mode') || 'scan',
                query: '',
                isScanning: false,
                html5QrCode: null,
                laserAnim: 'animate-ping',

                init() {
                    if(this.activeTab === 'scan') {
                        setTimeout(() => this.toggleCamera(), 300);
                    } else {
                        setTimeout(() => document.getElementById('searchInput').focus(), 100);
                    }
                },

                toggleCamera() {
                    if (this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        });
                    } else {
                        if(!this.html5QrCode) this.html5QrCode = new Html5Qrcode("reader");
                        
                        let config = { fps: 10, qrbox: { width: 250, height: 250 } };
                        this.html5QrCode.start(
                            { facingMode: "environment" }, 
                            config,
                            (decodedText) => {
                                if (navigator.vibrate) window.navigator.vibrate(200);
                                this.html5QrCode.stop().then(() => {
                                    this.isScanning = false;
                                    this.activeTab = 'text';
                                    this.query = decodedText;
                                    setTimeout(() => document.getElementById('searchForm').submit(), 100);
                                });
                            },
                            (err) => {})
                        .then(() => {
                            this.isScanning = true;
                            this.laserAnim = 'animate-scan-laser';
                        })
                        .catch((err) => {
                            alert("No se pudo iniciar la cámara.");
                            this.activeTab = 'text';
                        });
                    }
                }
            }));
        });
    </script>
    <style>
        @keyframes scan-laser {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan-laser {
            animation: scan-laser 2.5s infinite linear;
        }
    </style>
</x-technician-layout>
