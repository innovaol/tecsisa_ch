<x-technician-layout>
    <div class="px-5 pt-6 max-w-5xl mx-auto md:py-10 space-y-8">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Gestión de <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Infraestructura</span>
            </h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Control maestro de activos, sistemas y topología hospitalaria</p>
        </div>

        <!-- KPI Grid Rápida -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-theme-card border border-theme p-5 rounded-3xl shadow-lg transition-colors duration-500">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Total Activos</span>
                <span class="text-2xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ \App\Models\Equipment::count() }}</span>
            </div>
            <div class="bg-theme-card border border-theme p-5 rounded-3xl shadow-lg transition-colors duration-500">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Ubicaciones</span>
                <span class="text-2xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ \App\Models\Location::count() }}</span>
            </div>
            <div class="hidden md:block bg-theme-card border border-theme p-5 rounded-3xl shadow-lg transition-colors duration-500">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Sistemas</span>
                <span class="text-2xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ \App\Models\System::count() }}</span>
            </div>
            <div class="hidden md:block bg-theme-card border border-theme p-5 rounded-3xl shadow-lg transition-colors duration-500">
                <span class="block text-[9px] font-black text-gray-500 uppercase tracking-tighter mb-1">Roles</span>
                <span class="text-2xl font-black transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">2</span>
            </div>
        </div>

        <!-- Menú de Administración -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <h3 class="col-span-full text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-2 px-1 flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-tecsisa-yellow"></span> Módulos de Configuración
            </h3>

            <!-- Gestión de Equipos -->
            <a href="{{ route('technician.equipment.list') }}" class="block group">
                <div class="h-full bg-theme-card border border-theme rounded-[2rem] p-6 flex items-center gap-5 hover:border-tecsisa-yellow/30 transition-all active:scale-[0.98] shadow-2xl">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-tecsisa-yellow/10 text-tecsisa-yellow flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-black group-hover:text-tecsisa-yellow transition-colors uppercase tracking-wider" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Activos</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight mt-1">Inventario maestro de hardware</p>
                    </div>
                    <div class="shrink-0 text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Sistemas Técnicos -->
            <a href="{{ route('technician.systems') }}" class="block group">
                <div class="h-full bg-theme-card border border-theme rounded-[2rem] p-6 flex items-center gap-5 hover:border-blue-400/30 transition-all active:scale-[0.98] shadow-2xl">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-black group-hover:text-blue-400 transition-colors uppercase tracking-wider" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Sistemas</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight mt-1">Configuración de protocolos</p>
                    </div>
                    <div class="shrink-0 text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Ubicaciones -->
            <a href="{{ route('technician.locations') }}" class="block group">
                <div class="h-full bg-theme-card border border-theme rounded-[2rem] p-6 flex items-center gap-5 hover:border-purple-400/30 transition-all active:scale-[0.98] shadow-2xl">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-purple-500/10 text-purple-500 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-black group-hover:text-purple-400 transition-colors uppercase tracking-wider" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Ubicaciones</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight mt-1">Topología de sitios técnicos</p>
                    </div>
                    <div class="shrink-0 text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>

            <!-- Racks & Topología -->
            <a href="{{ route('rack.builder') }}" class="block group">
                <div class="h-full bg-theme-card border border-theme rounded-[2rem] p-6 flex items-center gap-5 hover:border-orange-400/30 transition-all active:scale-[0.98] shadow-2xl">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-base font-black group-hover:text-orange-400 transition-colors uppercase tracking-wider" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Racks y Puertos</h4>
                        <p class="text-[11px] text-gray-500 font-bold uppercase tracking-tight mt-1">Gestión de unidades y patchpanels</p>
                    </div>
                    <div class="shrink-0 text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="mt-12 text-center">
            <p class="text-[10px] text-gray-600 font-black uppercase tracking-[0.2em] leading-relaxed">Central de Control y Mantenimiento<br>Hospital Ciudad Hospitalaria</p>
        </div>
    </div>
</x-technician-layout>
