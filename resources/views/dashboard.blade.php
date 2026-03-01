<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-tecsisa-dark leading-tight">
            {{ __('Visión Global de Infraestructura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPIs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Card 1 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-tecsisa-yellow">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Equipos Operativos</div>
                        <div class="mt-2 text-3xl font-semibold text-tecsisa-dark">0</div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-tecsisa-yellow">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Trabajos Pendientes</div>
                        <div class="mt-2 text-3xl font-semibold text-tecsisa-dark">0</div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-tecsisa-yellow">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Metros de Cable Instalado</div>
                        <div class="mt-2 text-3xl font-semibold text-tecsisa-dark">0 m</div>
                    </div>
                </div>
            </div>

            <!-- Main Panel -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-tecsisa-dark mb-4">Últimas Intervenciones</h3>
                    <div class="text-gray-500 text-sm italic">
                        No hay datos registrados aún. Inicia instalando equipos en el catálogo.
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
