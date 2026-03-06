<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <div class="mb-8">
            <h2 class="text-2xl md:text-3xl font-black text-white leading-tight mt-1">Generador de <span class="text-tecsisa-yellow uppercase tracking-widest text-xs font-black">Reporte Semanal</span></h2>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1 px-1">Consolida todas las tareas finalizadas en un único PDF técnico profesional</p>
        </div>

        <div class="bg-[#0f1217]/60 backdrop-blur-xl rounded-[2.5rem] border border-white/5 overflow-hidden shadow-2xl">
            <div class="p-8 md:p-10">
                <form action="{{ route('reports.weekly.generate') }}" method="POST" target="_blank">
                    @csrf
                    <div class="space-y-8">
                        <!-- Selección de Sistema -->
                        <div>
                            <label class="block text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.2em] mb-4 ml-1">1. Seleccionar Sistema Técnico</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($systems as $system)
                                <label class="relative flex items-center p-4 cursor-pointer rounded-2xl bg-black/40 border border-white/5 hover:border-tecsisa-yellow/30 transition group">
                                    <input type="radio" name="system_id" value="{{ $system->id }}" required class="w-4 h-4 text-tecsisa-yellow bg-black border-white/10 focus:ring-tecsisa-yellow">
                                    <div class="ml-4">
                                        <p class="text-xs font-black text-white group-hover:text-tecsisa-yellow transition">{{ $system->name }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter">{{ $system->equipments_count }} equipos registrados</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rango de Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Fecha Inicio</label>
                                <input type="date" name="start_date" required value="{{ date('Y-m-d', strtotime('monday this week')) }}"
                                       class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-12 px-4 font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Fecha Cierre</label>
                                <input type="date" name="end_date" required value="{{ date('Y-m-d', strtotime('sunday this week')) }}"
                                       class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-12 px-4 font-bold">
                            </div>
                        </div>

                        <!-- Texto de Portada -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Título del Reporte (Portada)</label>
                            <input type="text" name="report_title" required value="SISTEMAS ESPECIALES - SEMANA" 
                                   class="w-full bg-black/40 border-white/10 rounded-xl text-white text-sm focus:border-tecsisa-yellow focus:ring-tecsisa-yellow transition h-12 px-4 font-bold">
                        </div>

                        <!-- Botón de Acción -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black py-5 rounded-2xl text-xs uppercase tracking-[0.2em] shadow-xl shadow-yellow-400/20 transition transform active:scale-[0.98] flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Compilar Reporte Maestro (PDF)
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Guía Rápida -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 opacity-60">
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-tecsisa-yellow mb-3 border border-white/5">1</div>
                <p class="text-[9px] font-black text-white uppercase tracking-widest">Filtra por Sistema</p>
                <p class="text-[8px] text-gray-500 font-bold mt-1">Voz y Datos, CCTV, etc.</p>
            </div>
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-tecsisa-yellow mb-3 border border-white/5">2</div>
                <p class="text-[9px] font-black text-white uppercase tracking-widest">Define Periodo</p>
                <p class="text-[8px] text-gray-500 font-bold mt-1">Lunes a Sábado</p>
            </div>
            <div class="flex flex-col items-center text-center p-4">
                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-tecsisa-yellow mb-3 border border-white/5">3</div>
                <p class="text-[9px] font-black text-white uppercase tracking-widest">Exportación Instantánea</p>
                <p class="text-[8px] text-gray-500 font-bold mt-1">Formato listo para entrega</p>
            </div>
        </div>
    </div>
</x-app-layout>
