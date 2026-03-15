<x-app-layout>
    <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-8">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl"></div>
            <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Generador de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Reporte Semanal</span>
            </h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Consolida tareas finalizadas en un único documento maestro profesional</p>
        </div>

        <div class="bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
            <div class="p-8 md:p-10">
                <form action="{{ route('reports.weekly.generate') }}" method="POST" target="_blank">
                    @csrf
                    <div class="space-y-8">
                        <!-- Selección de Sistema -->
                        <div>
                            <label class="block text-[10px] font-black text-tecsisa-yellow uppercase tracking-[0.2em] mb-4 ml-1">1. Seleccionar Sistema Técnico</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($systems as $system)
                                <label class="relative flex items-center p-5 cursor-pointer rounded-2xl bg-black/10 border border-theme hover:border-tecsisa-yellow/30 transition-all duration-300 group">
                                    <input type="radio" name="system_id" value="{{ $system->id }}" required class="w-4 h-4 text-tecsisa-yellow bg-black border-white/10 focus:ring-tecsisa-yellow">
                                    <div class="ml-4">
                                        <p class="text-[13px] font-black uppercase transition-colors duration-500 group-hover:text-tecsisa-yellow" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $system->name }}</p>
                                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1">{{ $system->equipments_count }} equipos registrados</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rango de Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Fecha Inicio</label>
                                <input type="text" name="start_date" id="start_date" required value="{{ date('d/m/Y', strtotime('monday this week')) }}"
                                       class="w-full bg-black/20 border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-0 transition h-12 px-4 font-bold transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Fecha Cierre</label>
                                <input type="text" name="end_date" id="end_date" required value="{{ date('d/m/Y', strtotime('sunday this week')) }}"
                                       class="w-full bg-black/20 border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-0 transition h-12 px-4 font-bold transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const fpConfig = {
                                    dateFormat: "Y-m-d", // Backend
                                    altInput: true,
                                    altFormat: "d/m/Y", // Visual
                                    locale: "es",
                                    allowInput: true,
                                };
                                flatpickr("#start_date", fpConfig);
                                flatpickr("#end_date", fpConfig);
                            });
                        </script>

                        <!-- Texto de Portada -->
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Título del Reporte (Portada)</label>
                            <input type="text" name="report_title" required value="SISTEMAS ESPECIALES - SEMANA" 
                                   class="w-full bg-black/20 border-theme rounded-xl text-sm focus:border-tecsisa-yellow focus:ring-0 transition h-12 px-4 font-bold transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        </div>

                        <!-- Botón de Acción -->
                        <div class="pt-4">
                            <button type="submit" class="w-full bg-tecsisa-yellow hover:bg-yellow-400 text-tecsisa-dark font-black py-5 rounded-2xl text-xs uppercase tracking-[0.2em] shadow-xl shadow-yellow-400/20 transition transform active:scale-[0.98] flex items-center justify-center gap-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                Exportar PDF
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
