<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ showCreateModal: false }">
        <!-- Header Section: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-6 sm:p-10 mb-6 sm:mb-10 transition-all duration-500 shadow-xl relative flex flex-col md:flex-row md:items-center justify-between gap-8">
            <!-- Decorative Orbs (Clipped) -->
            <div class="absolute inset-0 overflow-hidden rounded-[2.5rem] pointer-events-none">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-tecsisa-yellow/5 rounded-full blur-3xl"></div>
            </div>
            <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                <a href="{{ route('dashboard') }}" class="w-11 h-11 flex items-center justify-center bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group shrink-0">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl sm:text-4xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Personal y <span class="text-tecsisa-yellow uppercase tracking-widest text-xs sm:text-sm font-black">Accesos</span>
                        <div class="group relative inline-block ml-1">
                            <svg class="w-5 h-5 text-theme-muted cursor-help p-0.5 hover:text-tecsisa-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0114 0z"></path></svg>
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-64 p-4 bg-black/95 text-[11px] text-white rounded-2xl opacity-0 group-hover:opacity-100 transition-all pointer-events-none z-[100] border border-theme shadow-2xl normal-case font-bold backdrop-blur-md">
                                <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-black/95 border-b border-r border-theme rotate-45"></div>
                                Control maestro de roles, perfiles y credenciales del personal técnico.
                            </div>
                        </div>
                    </h2>
                    <p class="text-[10px] sm:text-xs text-theme-muted font-bold uppercase tracking-widest mt-1 sm:mt-2 px-1">Gestión de perfiles y credenciales</p>
                </div>
            </div>
            <button @click="showCreateModal = true" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black px-6 py-4 rounded-2xl text-[10px] uppercase tracking-widest transition-all duration-300 shadow-[0_15px_40px_rgba(255,209,0,0.3)] flex items-center justify-center gap-3 active:scale-95 whitespace-nowrap group w-full md:w-auto">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Nuevo
            </button>
        </div>

        <!-- Users Table: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-500">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-black/10 border-b border-theme transition-colors duration-500" :class="theme === 'light' ? 'bg-slate-50' : ''">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Colaborador</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Identificación</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-500 uppercase tracking-[0.3em]">Rango / Nivel</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="border-b border-theme last:border-0 hover:bg-theme-table-row-hover transition-colors duration-500">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-tecsisa-yellow/10 flex items-center justify-center border border-tecsisa-yellow/20 text-tecsisa-yellow font-black text-sm shadow-sm transition-colors duration-500">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-black text-sm tracking-tight transition-colors duration-500 uppercase" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-xs font-bold uppercase tracking-tighter transition-colors duration-500" :class="theme === 'light' ? 'text-slate-500' : 'text-gray-400'">{{ $user->email }}</span>
                            </td>
                            <td class="px-8 py-5">
                                @foreach($user->roles as $role)
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $role->name == 'Administrador' ? 'bg-tecsisa-yellow text-black shadow-lg shadow-yellow-400/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="p-2.5 bg-theme-card rounded-xl border border-theme text-gray-400 hover:text-tecsisa-yellow transition-all shadow-md active:scale-90">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar acceso definitivamente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-red-500/5 rounded-xl border border-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition-all shadow-md active:scale-90">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create User Modal -->
        <div x-show="showCreateModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[200] overflow-y-auto" 
             style="display: none;">
            
            <div class="min-h-screen px-4 text-center flex items-center justify-center py-10">
                <!-- Overlay -->
                <div @click="showCreateModal = false" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>

                <!-- Modal Container -->
                <div class="inline-block w-full max-w-xl relative bg-theme-card border border-theme rounded-[2.5rem] shadow-2xl p-6 sm:p-12 text-left align-middle transform transition-all overflow-hidden transition-colors duration-500">
                    
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-tecsisa-yellow/5 blur-[80px] pointer-events-none"></div>

                    <!-- Close button -->
                    <button @click="showCreateModal = false" class="absolute top-8 right-8 w-12 h-12 rounded-2xl bg-theme/5 border border-theme flex items-center justify-center text-theme-muted hover:text-tecsisa-yellow transition active:scale-95 shadow-lg group">
                        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <h3 class="text-3xl font-black text-theme mb-12 pr-16 flex items-center gap-4 leading-tight uppercase tracking-tight">
                        Nuevo<br>
                        <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black underline decoration-2 underline-offset-8">Colaborador</span>
                    </h3>
                    
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-8">
                        @csrf
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-theme-muted uppercase tracking-[0.2em] px-2">Nombre Completo</label>
                            <input type="text" name="name" required placeholder="Ej: Juan Pérez"
                                class="w-full bg-theme/5 border border-theme rounded-2xl px-6 py-5 focus:border-tecsisa-yellow focus:ring-0 transition font-black text-sm uppercase tracking-wider text-theme placeholder-theme-muted shadow-inner">
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-theme-muted uppercase tracking-[0.2em] px-2">Correo Institucional</label>
                            <input type="email" name="email" required placeholder="juan.perez@tecsisa.com"
                                class="w-full bg-theme/5 border border-theme rounded-2xl px-6 py-5 focus:border-tecsisa-yellow focus:ring-0 transition font-black text-sm tracking-wider text-theme placeholder-theme-muted shadow-inner">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-theme-muted uppercase tracking-[0.2em] px-2">Contraseña</label>
                                <input type="password" name="password" required placeholder="********"
                                    class="w-full bg-theme/5 border border-theme rounded-2xl px-6 py-5 focus:border-tecsisa-yellow focus:ring-0 transition font-black text-sm tracking-widest text-theme placeholder-theme-muted shadow-inner">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-theme-muted uppercase tracking-[0.2em] px-2">Confirmar</label>
                                <input type="password" name="password_confirmation" required placeholder="********"
                                    class="w-full bg-theme/5 border border-theme rounded-2xl px-6 py-5 focus:border-tecsisa-yellow focus:ring-0 transition font-black text-sm tracking-widest text-theme placeholder-theme-muted shadow-inner">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-theme-muted uppercase tracking-[0.2em] px-2">Rol Asignado</label>
                            <select name="role" required
                                class="w-full bg-theme/5 border border-theme rounded-2xl px-6 py-5 focus:border-tecsisa-yellow focus:ring-0 transition font-black text-sm tracking-wider text-theme placeholder-theme-muted shadow-inner appearance-none">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" class="bg-theme text-theme">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="w-full bg-tecsisa-yellow text-black font-black px-6 py-4 rounded-2xl text-[10px] uppercase tracking-widest shadow-xl hover:bg-yellow-400 transition transform active:scale-95 order-1 sm:order-2">Guardar</button>
                            <button type="button" @click="showCreateModal = false" class="w-full px-6 py-4 border border-theme rounded-2xl text-theme-muted font-bold uppercase tracking-widest text-[10px] hover:bg-theme/5 transition order-2 sm:order-1 text-center font-black">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
