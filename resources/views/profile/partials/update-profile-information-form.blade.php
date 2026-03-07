<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <div>
                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] mb-2 pl-1">Nombre Completo</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-theme-muted transition group-focus-within:text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                           class="w-full bg-theme/5 border border-theme rounded-2xl text-[11px] font-bold h-12 pl-12 pr-4 focus:ring-0 focus:border-tecsisa-yellow transition-all duration-300 text-theme">
                </div>
                @error('name') <p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] mb-2 pl-1">Correo Electrónico</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-theme-muted transition group-focus-within:text-tecsisa-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full bg-theme/5 border border-theme rounded-2xl text-[11px] font-bold h-12 pl-12 pr-4 focus:ring-0 focus:border-tecsisa-yellow transition-all duration-300 text-theme">
                </div>
                @error('email') <p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-tecsisa-yellow/10 transition active:scale-95">
                Guardar Cambios
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-3 py-2 rounded-lg">
                    Actualizado Correctamente
                </p>
            @endif
        </div>
    </form>
</section>
