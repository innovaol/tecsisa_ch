<section>
    <header class="hidden">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-4">
            <div>
                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] mb-2 pl-1">Contraseña Actual</label>
                <input type="password" name="current_password" required
                       class="w-full bg-theme/5 border border-theme rounded-2xl text-[11px] font-bold h-12 px-4 focus:ring-0 focus:border-blue-500 transition-all duration-300 text-theme shadow-inner">
                @error('current_password', 'updatePassword') <p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] mb-2 pl-1">Nueva Contraseña</label>
                <input type="password" name="password" required
                       class="w-full bg-theme/5 border border-theme rounded-2xl text-[11px] font-bold h-12 px-4 focus:ring-0 focus:border-blue-500 transition-all duration-300 text-theme shadow-inner">
                @error('password', 'updatePassword') <p class="mt-1 text-[10px] text-red-500 font-bold uppercase">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[8px] font-black text-theme-muted uppercase tracking-[0.2em] mb-2 pl-1">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-theme/5 border border-theme rounded-2xl text-[11px] font-bold h-12 px-4 focus:ring-0 focus:border-blue-500 transition-all duration-300 text-theme shadow-inner">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-black py-4 px-8 rounded-2xl text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-blue-500/10 transition active:scale-95">
                Guardar
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-[10px] font-black text-emerald-400 uppercase tracking-widest bg-emerald-500/10 px-3 py-2 rounded-lg">
                    Contraseña Guardada
                </p>
            @endif
        </div>
    </form>
</section>
