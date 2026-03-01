<x-technician-layout>
    <script src="https://unpkg.com/html5-qrcode"></script>
    
    <div class="fixed top-0 inset-x-0 z-40 bg-[#0a0d14]/90 backdrop-blur-xl border-b border-white/5 pt-safe">
        <div class="px-4 py-3 flex items-center justify-between">
            <h1 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Asistente de Busqueda
            </h1>
        </div>
    </div>
    
    <div class="pt-20 pb-24 px-5" x-data="scannerApp()">
        
        <!-- Toggle Tabs -->
        <div class="flex bg-black/40 border border-white/5 p-1 rounded-full mb-6 relative">
            <div class="absolute inset-y-1 w-[calc(50%-4px)] bg-[#1a202c] rounded-full shadow transition-all duration-300 ease-out" :class="activeTab === 'scan' ? 'left-1' : 'left-[50%]'"></div>
            <button @click="activeTab = 'scan'" class="flex-1 py-3 text-xs font-bold uppercase tracking-widest text-center relative z-10 transition-colors" :class="activeTab === 'scan' ? 'text-white' : 'text-gray-500'">
                Escáner Óptico
            </button>
            <button @click="activeTab = 'text'" class="flex-1 py-3 text-xs font-bold uppercase tracking-widest text-center relative z-10 transition-colors" :class="activeTab === 'text' ? 'text-white' : 'text-gray-500'">
                Ingreso Manual
            </button>
        </div>

        <!-- CAMERA SCANNER VIEW -->
        <div x-show="activeTab === 'scan'" class="flex flex-col items-center">
            
            <div class="w-full max-w-sm aspect-square bg-black rounded-3xl overflow-hidden relative border-2 border-white/10 shadow-[0_10px_40px_rgba(0,0,0,0.5)] mb-6">
                <!-- Marco/Guía de Escaneo -->
                <div class="absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
                    <div class="w-48 h-48 border-2 border-tecsisa-yellow/50 rounded-2xl relative">
                        <!-- Esquinas iluminadas -->
                        <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-tecsisa-yellow"></div>
                        <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-tecsisa-yellow"></div>
                        <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-tecsisa-yellow"></div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-tecsisa-yellow"></div>
                        <!-- Linea de escaneo laser -->
                        <div class="absolute w-full h-[2px] bg-red-500 shadow-[0_0_15px_rgba(239,68,68,1)]" :class="laserAnim"></div>
                    </div>
                </div>
                
                <!-- Contenedor Real de la Camara -->
                <div id="reader" class="w-full h-full object-cover"></div>
            </div>
            
            <h3 class="text-white font-bold mb-2">Enfoca cualquier código de barras o QR</h3>
            <p class="text-[10px] text-gray-500 text-center max-w-[250px] uppercase font-bold tracking-widest leading-relaxed">
                Apunta hacia la placa serial original del fabricante o las etiquetas de Tecsisa.
            </p>

            <!-- Start/Stop Cam Feedback -->
            <button @click="toggleCamera()" class="mt-8 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest transition shadow-lg flex items-center gap-2" :class="isScanning ? 'bg-red-500/10 text-red-500 border border-red-500/20' : 'bg-tecsisa-yellow text-black shadow-[0_5px_20px_rgba(255,209,0,0.3)]'">
                <span x-text="isScanning ? 'Detener Cámara' : 'Iniciar Escáner'"></span>
                <svg x-show="!isScanning" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <svg x-show="isScanning" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- MANUAL SEARCH VIEW -->
        <div x-show="activeTab === 'text'" style="display: none;" class="flex flex-col">
            <div class="bg-gradient-to-br from-[#12161f] to-[#0a0d14] rounded-3xl border border-white/10 p-6 shadow-2xl relative overflow-hidden text-center mb-8">
                <!-- Deco -->
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

                <div class="w-16 h-16 bg-[#1a202c] border border-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                
                <h2 class="text-xl font-bold text-white mb-2">Busqueda Predictiva</h2>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Por ID, Nombre o Sala</p>
            </div>

            <form action="{{ route('technician.scanner.search') }}" method="POST" id="searchForm">
                @csrf
                <div class="relative">
                    <input type="text" name="query" x-model="query" id="searchInput" placeholder="Ej. CAM-EXT-04..." 
                           class="w-full bg-black/50 border border-white/10 text-white font-mono text-center rounded-2xl px-5 py-5 focus:ring-2 focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-xl tracking-widest shadow-inner placeholder-gray-700">
                    
                    <button type="submit" :disabled="query.length < 3" 
                            class="mt-4 w-full px-6 py-4 rounded-2xl font-black uppercase tracking-widest transition flex items-center justify-center gap-2 text-sm"
                            :class="{'opacity-50 cursor-not-allowed bg-[#1a202c] text-gray-500 border border-white/5': query.length < 3, 'bg-tecsisa-yellow hover:bg-yellow-400 text-black shadow-[0_10px_30px_rgba(255,209,0,0.3)]': query.length >= 3}">
                        Consultar Base de Datos
                        <svg x-show="query.length >= 3" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Scanner Script Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('scannerApp', () => ({
                activeTab: 'scan',
                query: '',
                isScanning: false,
                html5QrCode: null,
                laserAnim: 'animate-ping',

                init() {
                    this.$watch('activeTab', value => {
                        if(value === 'scan') {
                            // Automatically start if it was stopped
                            if(!this.isScanning) this.toggleCamera();
                        } else {
                            // Stop camera if navigating to text search to save battery
                            if(this.isScanning) this.toggleCamera();
                            // Auto focus input
                            setTimeout(() => document.getElementById('searchInput').focus(), 100);
                        }
                    });

                    // Start camera aggressively by default on page load (give minimal delay for DOM to render)
                    setTimeout(() => {
                        if(this.activeTab === 'scan') this.toggleCamera();
                    }, 500);
                },

                toggleCamera() {
                    if (this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.isScanning = false;
                        }).catch(err => {
                            console.log("Failed to stop camera:", err);
                        });
                    } else {
                        if(!this.html5QrCode) {
                            // Use standard reader element
                            this.html5QrCode = new Html5Qrcode("reader");
                        }
                        
                        // We ask for the back camera specifically for scanners
                        let config = { fps: 10, qrbox: { width: 250, height: 250 } };
                        
                        this.html5QrCode.start(
                            { facingMode: "environment" }, 
                            config,
                            (decodedText, decodedResult) => {
                                // SUCCESS CALLBACK
                                // Vibrate phone if mobile and success
                                if (navigator.vibrate) window.navigator.vibrate(200);
                                
                                // Stop scanner aggressively to avoid loop scanning
                                this.html5QrCode.stop().then(() => {
                                    this.isScanning = false;
                                    
                                    // Switch to form, fill input and submit artificially
                                    this.activeTab = 'text';
                                    this.query = decodedText;
                                    
                                    // Give Alpine time to update the DOM then submit
                                    setTimeout(() => {
                                        document.getElementById('searchForm').submit();
                                    }, 100);
                                    
                                });
                            },
                            (errorMessage) => {
                                // Silent fallbacks for frames where it can't find a barcode yet
                            })
                        .then(() => {
                            this.isScanning = true;
                            // Add a playful up and down laser animation via CSS classes
                            this.laserAnim = 'animate-scan-laser';
                        })
                        .catch((err) => {
                            alert("No se pudo iniciar la cámara. Verifica los permisos de tu navegador o si estás en HTTPS.");
                            this.activeTab = 'text'; // fail gracefully to manual search
                        });
                    }
                }
            }));
        });
    </script>
    <style>
        /* Custom animation for the laser line */
        @keyframes scan-laser {
            0% { top: 0%; opacity: 0;}
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan-laser {
            animation: scan-laser 2.5s infinite linear;
        }
        
        /* Make the default html5-qrcode controls aesthetic (or hide them since we trigger them via custom buttons) */
        #reader__dashboard_section_csr span, #reader__dashboard_section_csr button {
            color: #fff;
            background: #FFD100;
            border: none;
            border-radius: 8px;
            padding: 5px 10px;
        }
    </style>
</x-technician-layout>
