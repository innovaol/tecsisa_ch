<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white tracking-wide leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            {{ __('Resultados de Búsqueda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-tecsisa-card backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.5)] border border-white/10 overflow-hidden relative">
                
                <div class="p-6 border-b border-white/5 bg-black/30 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-white">Equipos Coincidentes con "{{ $query }}"</h3>
                    <span class="text-xs text-gray-500 bg-white/5 px-3 py-1 rounded-full border border-white/10">{{ count($results) }} resultados</span>
                </div>

                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/5 text-xs font-semibold tracking-wide text-gray-400 uppercase border-b border-white/10">
                                <th class="py-4 pl-6 pr-4">ID de Placa</th>
                                <th class="py-4 pr-4">Nombre</th>
                                <th class="py-4 pr-6 text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($results as $res)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="py-4 pl-6 pr-4 font-mono text-sm tracking-tight text-tecsisa-yellow font-bold">{{ $res->internal_id }}</td>
                                <td class="py-4 pr-4 text-sm font-medium text-gray-200 group-hover:text-white">{{ $res->name }}</td>
                                <td class="py-4 pr-6 text-right">
                                    <form action="{{ route('scanner.search') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="query" value="{{ $res->internal_id }}">
                                        <button type="submit" class="text-blue-400 hover:text-white font-medium text-xs border border-blue-500/30 px-3 py-1.5 rounded bg-blue-500/10 hover:bg-blue-500 transition">
                                            Seleccionar Exactamente
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="py-8 text-center text-gray-500">No se encontraron equipos bajo ese criterio.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                 <a href="{{ route('scanner.index') }}" class="text-gray-400 hover:text-white transition text-sm">
                     &larr; Volver al Buscador
                 </a>
            </div>
        </div>
    </div>
</x-app-layout>
