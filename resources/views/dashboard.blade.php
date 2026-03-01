<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-tecsisa-dark leading-tight">
            {{ __('Visión Global de Infraestructura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider group-hover:text-tecsisa-dark transition">Equipos Operativos</div>
                            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-gray-900">
                            0
                            <span class="ml-2 text-sm font-medium text-gray-400">total</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider group-hover:text-tecsisa-dark transition">Trabajos Pendientes</div>
                            <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-gray-900">
                            0
                            <span class="ml-2 text-sm font-medium text-gray-400">tickets</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:border-tecsisa-yellow/50 transition duration-300 group">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider group-hover:text-tecsisa-dark transition">Fibra / Cobre</div>
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline text-4xl font-extrabold text-gray-900">
                            0<span class="text-xl text-gray-500 ml-1">m</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-lg font-bold text-tecsisa-dark">Últimas Intervenciones Registradas</h3>
                    <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver Historial Completo &rarr;</button>
                </div>
                <div class="p-8 text-center bg-white flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium text-lg">No hay datos registrados aún</p>
                    <p class="text-gray-400 text-sm mt-1 mb-6">Inicia instalando equipos en el catálogo para ver actividad.</p>
                    <button class="px-6 py-2 bg-tecsisa-dark hover:bg-gray-800 text-white rounded-xl shadow shadow-gray-900/20 text-sm font-semibold transition">
                        + Añadir Equipo Físico
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
