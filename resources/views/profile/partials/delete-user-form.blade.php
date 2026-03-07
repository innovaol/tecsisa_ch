<section class="space-y-6">
    <div class="bg-red-500/5 border border-red-500/10 p-6 rounded-3xl">
        <p class="text-[11px] text-gray-500 font-bold leading-relaxed transition-colors duration-500">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Por favor, asegúrate de haber respaldado cualquier información crítica antes de proceder.
        </p>
    </div>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="w-full sm:w-auto bg-theme-border hover:bg-red-600 hover:text-white border border-theme text-red-500 font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] transition-all duration-300 shadow-lg active:scale-95">
        Eliminar Cuenta Permanentemente
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 transition-colors duration-500" :class="theme === 'light' ? 'bg-white' : 'bg-[#12161f]'">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black uppercase tracking-widest mb-4 transition-colors duration-500" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                ¿Confirmar Eliminación?
            </h2>

            <p class="text-xs text-gray-500 mb-8 font-bold uppercase tracking-wide leading-relaxed">
                Esta acción es irreversible y borrará todo tu historial técnico. Introduce tu contraseña para verificar tu identidad.
            </p>

            <div class="mb-8">
                <label class="block text-[8px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 pl-1">Tu Contraseña de Acceso</label>
                <input type="password" name="password" required
                       class="w-full bg-black/10 border border-theme rounded-2xl text-[11px] font-bold h-12 px-4 focus:ring-0 focus:border-red-500 transition-all duration-300" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                @error('password', 'userDeletion') <p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest transition" :class="theme === 'light' ? 'hover:text-slate-900' : 'hover:text-white'">
                    Cancelar
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-red-500/10 transition">
                    Confirmar Eliminación
                </button>
            </div>
        </form>
    </x-modal>
</section>
