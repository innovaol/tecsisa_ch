<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            {{ __('Visión Global de Infraestructura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.1)] border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider group-hover:text-white transition">Equipos Operativos</div>
                            <div class="p-2 bg-green-500/10 rounded-lg text-green-400 border border-green-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-white">
                            {{ $equipos_operativos }}
                            <span class="ml-2 text-sm font-medium text-gray-500">equipos</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.1)] border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider group-hover:text-white transition">Trabajos Pendientes</div>
                            <div class="p-2 bg-yellow-500/10 rounded-lg text-tecsisa-yellow border border-yellow-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-white">
                            {{ $trabajos_pendientes }}
                            <span class="ml-2 text-sm font-medium text-gray-500">equipos en fallo</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-tecsisa-card backdrop-blur-md rounded-2xl shadow-[0_4px_30px_rgba(0,0,0,0.1)] border border-white/10 hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider group-hover:text-white transition">Fibra / Cobre</div>
                            <div class="p-2 bg-blue-500/10 rounded-lg text-blue-400 border border-blue-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-white">
                            {{ $cable_instalado }}<span class="text-xl text-gray-500 ml-1">metros</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-lg border border-white/10 overflow-hidden">
                <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <h3 class="text-lg font-bold text-white">Últimas Intervenciones Registradas</h3>
                    <button class="text-sm text-tecsisa-yellow hover:text-yellow-300 font-medium transition">Ver Historial Completo &rarr;</button>
                </div>
                <div class="p-8 text-center flex flex-col items-center justify-center">
                    @if($equipos_operativos > 0 || $trabajos_pendientes > 0)
                        <div class="overflow-x-auto w-full max-w-2xl mx-auto rounded-lg border border-white/5 bg-black/20">
                            <!-- Simulated Recent Activity Table -->
                            <div class="grid grid-cols-4 px-4 py-3 text-sm font-medium text-gray-400 border-b border-white/5 uppercase tracking-wider text-left">
                                <div>Equipo</div>
                                <div>Ubicación</div>
                                <div>Tipo Evento</div>
                                <div class="text-right">Fecha</div>
                            </div>
                            <div class="grid grid-cols-4 px-4 py-3 text-sm text-gray-300 items-center text-left hover:bg-white/5 transition">
                                <div class="font-bold text-tecsisa-yellow">SW-MDF-001</div>
                                <div>Cuarto Principal MDF</div>
                                <div>Alta en Catálogo</div>
                                <div class="text-right text-gray-500 text-xs">Ayer 14:00</div>
                            </div>
                            <div class="grid grid-cols-4 px-4 py-3 text-sm text-gray-300 items-center text-left hover:bg-white/5 transition border-t border-white/5">
                                <div class="font-bold text-red-400">CAM-QRO-001</div>
                                <div>Piso 2 - Quirófanos</div>
                                <div>Reporte de Daño</div>
                                <div class="text-right text-gray-500 text-xs">Hoy 09:15</div>
                            </div>
                        </div>
                        <div class="mt-6">
                           <a href="{{ route('catalog.index') }}" class="px-6 py-2 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark rounded-xl shadow-[0_0_15px_rgba(255,209,0,0.3)] text-sm font-semibold transition inline-block transform hover:scale-105">
                                Ir a Base de Datos de Red (BDR)
                           </a>
                        </div>
                    @else
                        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center border border-white/10 mb-4 shadow-[0_0_15px_rgba(255,209,0,0.1)]">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <p class="text-gray-300 font-medium text-lg">No hay datos registrados aún</p>
                        <p class="text-gray-500 text-sm mt-1 mb-6">Inicia instalando equipos en el catálogo para ver actividad.</p>
                        <a href="{{ route('catalog.index') }}" class="px-6 py-2 bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark rounded-xl shadow-[0_0_15px_rgba(255,209,0,0.3)] text-sm font-semibold transition inline-block transform hover:scale-105">
                            + Añadir Equipo Físico
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
