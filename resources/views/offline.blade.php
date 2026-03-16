<x-technician-layout :hideNav="false">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 text-center">
        <div class="w-24 h-24 bg-tecsisa-yellow/10 rounded-full flex items-center justify-center text-tecsisa-yellow mb-8 animate-pulse">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 2.829a4.978 4.978 0 01-1.414-2.83m-1.414 5.658a9 9 0 01-2.167-9.238m7.824 2.167a1 1 0 111.414 1.414m-1.414-1.414L3 3m8.293 8.293l1.414 1.414"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-black text-white mb-2 uppercase tracking-widest">Modo Offline Detectado</h1>
        <p class="text-gray-400 text-sm font-bold uppercase tracking-widest mb-8">Parece que no tienes conexión a internet en este momento.</p>
        
        <div class="bg-white/5 border border-white/10 rounded-3xl p-6 mb-8 max-w-sm">
            <p class="text-[10px] text-tecsisa-yellow font-black uppercase tracking-widest mb-4">Información del Sistema</p>
            <p class="text-xs text-gray-500 font-bold leading-relaxed">
                Tus datos de catálogo y tareas guardadas están disponibles. Puedes seguir trabajando y la sincronización se realizará automáticamente cuando recuperes la señal.
            </p>
        </div>
        
        <button onclick="window.location.reload()" class="bg-tecsisa-yellow text-black px-8 py-4 rounded-2xl font-black uppercase tracking-widest active:scale-95 transition-all shadow-lg shadow-yellow-400/20">
            Reintentar Conexión
        </button>
    </div>
</x-technician-layout>
