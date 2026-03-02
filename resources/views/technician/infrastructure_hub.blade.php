<x-technician-layout>
    <div class="px-5 pt-6 max-w-2xl mx-auto md:py-10 md:px-0">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-black text-white leading-tight">Panel de<br><span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Gestión</span></h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Gestión Centralizada (Versión Móvil)</p>
        </div>

        <!-- KPI Grid Rápida -->
        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-white/5 border border-white/10 p-4 rounded-3xl">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Total Activos</span>
                <span class="text-2xl font-black text-white">{{ \App\Models\Equipment::count() }}</span>
            </div>
            <div class="bg-white/5 border border-white/10 p-4 rounded-3xl">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Ubicaciones</span>
                <span class="text-2xl font-black text-white">{{ \App\Models\Location::count() }}</span>
            </div>
        </div>

        <!-- Menú de Administración -->
        <div class="space-y-4">
            <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 px-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tecsisa-yellow"></span> Módulos del Sistema
            </h3>

            <!-- Gestión de Equipos -->
            <a href="{{ route('technician.equipment.list') }}" class="block group">
                <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-[2rem] p-5 flex items-center gap-4 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] relative overflow-hidden shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-tecsisa-yellow/10 text-tecsisa-yellow flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-white group-hover:text-tecsisa-yellow transition-colors uppercase tracking-wider">Activos</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight">Inventario completo y búsqueda</p>
                    </div>
                    <div class="shrink-0 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Sistemas Técnicos -->
            <a href="{{ route('technician.systems') }}" class="block group">
                <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-[2rem] p-5 flex items-center gap-4 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-white group-hover:text-blue-400 transition-colors uppercase tracking-wider">Sistemas Téc.</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight">{{ \App\Models\System::count() }} definidos actualmente</p>
                    </div>
                    <div class="shrink-0 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Ubicaciones -->
            <a href="{{ route('technician.locations') }}" class="block group">
                <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-[2rem] p-5 flex items-center gap-4 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-white group-hover:text-purple-400 transition-colors uppercase tracking-wider">Ubicaciones</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight">Sitos, pisos y cuartos técnicos</p>
                    </div>
                    <div class="shrink-0 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Racks & Topología -->
            <a href="{{ route('rack.builder') }}" class="block group">
                <div class="bg-gradient-to-r from-[#12161f] to-[#0a0d14] border border-white/5 rounded-[2rem] p-5 flex items-center gap-4 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] shadow-xl">
                    <div class="w-14 h-14 shrink-0 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-white group-hover:text-orange-400 transition-colors uppercase tracking-wider">Racks</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight">Gestión de unidades y puertos</p>
                    </div>
                    <div class="shrink-0 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="mt-12 text-center">
            <p class="text-[10px] text-gray-600 font-black uppercase tracking-[0.2em] leading-relaxed">Versión optimizada para<br>supervisión en terreno</p>
        </div>
    </div>


</x-technician-layout>
