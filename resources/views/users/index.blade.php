<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ showCreateModal: false }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h2 class="text-3xl font-black text-white leading-tight">Usuarios y <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Accesos</span></h2>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Control de roles y credenciales del sistema</p>
            </div>
            <button @click="showCreateModal = true" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black px-6 py-3 rounded-2xl text-sm uppercase tracking-widest transition shadow-[0_8px_30px_rgba(255,209,0,0.3)] flex items-center gap-3 active:scale-95">
                <svg class="w-5 h-5 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Usuario
            </button>
        </div>

        <!-- Users Table -->
        <div class="bg-[#0f1217]/50 backdrop-blur-xl border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-white/[0.02] border-b border-white/5">
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Usuario</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Email</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em]">Rol / Nivel</th>
                            <th class="px-6 py-4 text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-3.5 text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-tecsisa-yellow/10 flex items-center justify-center border border-tecsisa-yellow/20 text-tecsisa-yellow font-black text-[10px]">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs">
                                <span class="text-gray-400 font-medium">{{ $user->email }}</span>
                            </td>
                            <td class="px-6 py-3.5 text-xs">
                                @foreach($user->roles as $role)
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider {{ $role->name == 'Administrador' ? 'bg-tecsisa-yellow/20 text-tecsisa-yellow border border-tecsisa-yellow/30' : 'bg-blue-500/20 text-blue-400 border border-blue-500/30' }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('users.edit', $user) }}" class="p-1.5 bg-white/5 rounded-lg border border-white/10 text-gray-400 hover:text-white transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Eliminar acceso definitivamente?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-500/10 rounded-lg border border-red-500/10 text-red-400 hover:bg-red-500/20 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                <div @click="showCreateModal = false" class="fixed inset-0 bg-[#05080f]/95 backdrop-blur-md transition-opacity"></div>

                <!-- Modal Container -->
                <div class="inline-block w-full max-w-lg relative bg-[#0f1217] border border-white/10 rounded-[2.5rem] shadow-2xl p-6 sm:p-10 text-left align-middle transform transition-all overflow-hidden">
                    
                    <!-- Close button -->
                    <button @click="showCreateModal = false" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <h3 class="text-2xl font-black text-white mb-8 pr-12 flex items-center gap-3 leading-tight uppercase tracking-tight">
                        Registrar Nuevo<br>
                        <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black underline decoration-2 underline-offset-8">Colaborador</span>
                    </h3>
                    
                    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre Completo</label>
                            <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="Ej: Rodrigo Ferrer">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Correo Electrónico</label>
                            <input type="email" name="email" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="correo@empresa.com">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Contraseña</label>
                                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="********">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Confirmar</label>
                                <input type="password" name="password_confirmation" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="********">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Rol Asignado</label>
                            <select name="role" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold appearance-none">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" class="bg-[#0f1217]">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-6 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="w-full bg-tecsisa-yellow text-black font-black px-6 py-4 rounded-2xl text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-yellow-400 transition transform active:scale-95 order-1 sm:order-2">Guardar Acceso</button>
                            <button type="button" @click="showCreateModal = false" class="w-full px-6 py-4 border border-white/10 rounded-2xl text-gray-400 font-bold uppercase tracking-widest text-xs hover:bg-white/5 transition order-2 sm:order-1 text-center">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
