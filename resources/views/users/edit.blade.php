<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header Section: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-10 mb-10 transition-all duration-500 shadow-xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-tecsisa-yellow/5 rounded-full blur-3xl"></div>
            <div class="flex items-center gap-6">
                <a href="{{ route('users.index') }}" class="p-3 bg-theme/5 border border-theme text-theme-muted hover:text-tecsisa-yellow rounded-2xl transition-all shadow-md active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h2 class="text-4xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                        Editar <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Colaborador</span>
                    </h2>
                    <p class="text-xs text-theme-muted font-bold uppercase tracking-widest mt-2 px-1">Actualizando perfil y permisos de {{ $user->name }}</p>
                </div>
            </div>
        </div>
        
        @if ($errors->any())
            <div class="mb-10 p-6 bg-red-500/10 border border-red-500/20 rounded-3xl animate-pulse">
                <ul class="list-disc list-inside text-xs text-red-500 font-black uppercase tracking-widest leading-relaxed">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Info Básica y Rol -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tarjeta: Información de Perfil -->
                <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 transition-all duration-500 shadow-xl relative overflow-hidden group">
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-tecsisa-yellow/5 blur-[80px] pointer-events-none"></div>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-tecsisa-yellow text-black flex items-center justify-center font-black text-xl shadow-lg shadow-yellow-400/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h3 class="text-lg font-black uppercase tracking-widest" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Datos Personales</h3>
                    </div>

                    <form action="{{ route('users.update', $user) }}" method="POST" id="updateUserForm" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre Completo</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                    class="w-full bg-theme-card border border-theme rounded-2xl px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" 
                                    :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                    class="w-full bg-theme-card border border-theme rounded-2xl px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" 
                                    :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Rol en el Sistema</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($roles as $role)
                                <label class="relative flex items-center p-5 rounded-2xl bg-theme-card border border-theme cursor-pointer hover:border-tecsisa-yellow/40 transition group-radio">
                                    <input type="radio" name="role" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="hidden peer">
                                    <div class="w-5 h-5 rounded-full border-2 border-theme peer-checked:border-tecsisa-yellow peer-checked:bg-tecsisa-yellow flex items-center justify-center transition-all">
                                        <div class="w-2 h-2 rounded-full bg-black opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="ml-4 font-black uppercase text-xs tracking-widest text-theme-muted peer-checked:text-tecsisa-yellow transition-colors">{{ $role->name }}</span>
                                    <div class="absolute inset-0 rounded-2xl border-2 border-transparent transition-all pointer-events-none peer-checked:border-tecsisa-yellow/30"></div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Password Section Hidden by default in Card -->
                        <div class="pt-6 border-t border-theme">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-500 flex items-center justify-center font-black text-xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <h3 class="text-lg font-black uppercase tracking-widest" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">Seguridad</h3>
                            </div>

                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-6 px-1 italic">* Deja en blanco para mantener la contraseña actual.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nueva Contraseña</label>
                                    <input type="password" name="password" 
                                        class="w-full bg-theme-card border border-theme rounded-2xl px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" 
                                        :class="theme === 'light' ? 'text-slate-800' : 'text-white'" placeholder="********">
                                </div>
                                
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Confirmar Contraseña</label>
                                    <input type="password" name="password_confirmation" 
                                        class="w-full bg-theme-card border border-theme rounded-2xl px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" 
                                        :class="theme === 'light' ? 'text-slate-800' : 'text-white'" placeholder="********">
                                </div>
                            </div>
                        </div>

                        <div class="pt-10 flex flex-col sm:flex-row gap-4">
                            <button type="submit" class="flex-1 bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black px-10 py-5 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all duration-300 shadow-xl shadow-tecsisa-yellow/20 active:scale-95">
                                Guardar Cambios en Maestro
                            </button>
                            <a href="{{ route('users.index') }}" class="px-10 py-5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-theme-muted hover:text-theme border border-theme transition-all duration-300 active:scale-95 text-center">
                                Cancelar Edición
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Columna Derecha: Resumen/Stats (Opcional) -->
            <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-white/5 border border-theme flex items-center justify-center text-tecsisa-yellow font-black text-xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-theme">
                            <div>
                                <span class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Miembro Desde</span>
                                <span class="text-[10px] font-bold" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'">{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Última Actividad</span>
                                <span class="text-[10px] font-bold" :class="theme === 'light' ? 'text-slate-700' : 'text-gray-300'">Hoy</span>
                            </div>
                        </div>

                        <div class="pt-4">
                            <span class="block text-[8px] font-black text-gray-500 uppercase tracking-widest mb-2">Rol Jerárquico</span>
                            <div class="inline-flex px-3 py-1.5 rounded-xl border border-tecsisa-yellow/30 bg-tecsisa-yellow/10 text-tecsisa-yellow text-[10px] font-black uppercase tracking-widest">
                                {{ $user->roles->first()->name ?? 'Sin Rol' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta: Seguridad del Password (Tips) -->
                <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 transition-all duration-500 shadow-xl border-dashed">
                    <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Política de Seguridad</h4>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-tecsisa-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-[10px] font-medium text-gray-500 leading-tight">Mínimo 8 caracteres alfanuméricos</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-tecsisa-yellow shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-[10px] font-medium text-gray-500 leading-tight">El cambio es de efecto inmediato</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
