<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-10">
            <h2 class="text-3xl font-black text-white leading-tight">Editar <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Acceso</span></h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Actualizando credenciales para {{ $user->name }}</p>
        </div>

        <!-- Edit Form -->
        <div class="bg-[#0f1217]/50 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-10 shadow-2xl overflow-hidden relative group">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-tecsisa-yellow/5 blur-[80px] group-hover:bg-tecsisa-yellow/10 transition-colors pointer-events-none"></div>

            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-8 relative z-10 max-w-2xl">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre Completo</label>
                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-white/5 border border-white/10 rounded-2x border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="Nombre">
                    </div>
                    
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-white/5 border border-white/10 rounded-2xl text-white px-5 py-4 focus:border-tecsisa-yellow focus:ring-0 transition font-bold" placeholder="E-mail">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Rol Asignado</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($roles as $role)
                        <label class="relative flex items-center p-5 rounded-2xl bg-white/5 border border-white/10 cursor-pointer hover:border-tecsisa-yellow/40 transition group-radio">
                            <input type="radio" name="role" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="hidden peer">
                            <div class="w-5 h-5 rounded-full border-2 border-white/20 peer-checked:border-tecsisa-yellow peer-checked:bg-tecsisa-yellow flex items-center justify-center transition-all">
                                <div class="w-2 h-2 rounded-full bg-black opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </div>
                            <span class="ml-4 font-black uppercase text-xs tracking-widest text-gray-400 peer-checked:text-white transition-colors">{{ $role->name }}</span>
                            <div class="absolute inset-0 rounded-2xl border-2 border-transparent transition-all pointer-events-none peer-checked:border-tecsisa-yellow/30"></div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="pt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('users.index') }}" class="px-8 py-4 border border-white/10 rounded-2xl text-gray-400 font-bold uppercase tracking-widest text-xs hover:bg-white/5 transition flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver al Listado
                    </a>
                    <button type="submit" class="bg-tecsisa-yellow text-black font-black px-12 py-4 rounded-2xl text-xs uppercase tracking-[0.2em] shadow-xl hover:bg-yellow-400 transition transform active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
