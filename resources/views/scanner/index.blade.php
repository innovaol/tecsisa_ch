<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            {{ __('Buscador Técnico de Equipos / Identificador') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ query: '' }">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-lg border border-white/10 overflow-hidden">
                <div class="p-8 text-center bg-black/30 w-full relative">
                    <!-- Decoración visual -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-transparent pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="w-20 h-20 bg-tecsisa-dark border-2 border-tecsisa-yellow/50 rounded-full flex items-center justify-center mb-6 shadow-[0_0_25px_rgba(255,209,0,0.2)]">
                            <svg class="w-10 h-10 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Identificar Equipo Físico</h3>
                        <p class="text-gray-400 max-w-md mx-auto mb-8 text-sm">
                            Escribe el ID Interno de la Placa del dispositivo (Ej. SW-MDF-001) para iniciar una intervención técnica o consultar su ficha técnica directamente de la Base de Datos.
                        </p>
                        
                        <form action="{{ route('scanner.search') }}" method="POST" class="w-full max-w-md">
                            @csrf
                            <div class="flex">
                                <input type="text" name="query" x-model="query" required autofocus placeholder="Ingresar ID Interno o Modelo..." 
                                       class="w-full bg-black/50 border border-white/20 text-white rounded-l-xl px-5 py-4 focus:ring-2 focus:ring-tecsisa-yellow focus:border-tecsisa-yellow text-lg tracking-wider font-mono shadow-inner">
                                <button type="submit" :disabled="query.length < 3" 
                                        :class="{'opacity-50 cursor-not-allowed bg-gray-600 text-gray-400': query.length < 3, 'bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark hover:scale-105 shadow-[0_0_20px_rgba(255,209,0,0.3)]': query.length >= 3}"
                                        class="px-6 py-4 rounded-r-xl font-bold transition duration-300 flex items-center justify-center">
                                    Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="bg-white/5 border-t border-white/10 p-6 flex items-center justify-center gap-6">
                    <div class="text-center">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Rápido y Seguro</p>
                        <p class="text-gray-300 text-sm">Búsqueda Directa BDR</p>
                    </div>
                    <div class="h-8 w-px bg-white/10"></div>
                    <div class="text-center">
                        <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Evita Errores</p>
                        <p class="text-gray-300 text-sm">Formularios Dinámicos Según Equipo</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
