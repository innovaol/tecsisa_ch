<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="text-center mb-8">
            <h2 class="text-2xl font-semibold text-white">Plataforma Medi-Infra</h2>
            <p class="text-sm text-gray-400 mt-2">Ingresa tus credenciales para acceder al panel de control.</p>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300">Correo Electrónico</label>
            <div class="mt-1 relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                class="block w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-tecsisa-yellow focus:border-transparent transition duration-200" placeholder="admin@tecsisa.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300">Contraseña</label>
            <div class="mt-1 relative">
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                class="block w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-tecsisa-yellow focus:border-transparent transition duration-200" placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded bg-white/5 border-white/10 text-tecsisa-yellow focus:ring-tecsisa-yellow focus:ring-offset-gray-900" name="remember">
                <span class="ms-2 text-sm text-gray-400 hover:text-white transition">Recordar sesión</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-tecsisa-yellow hover:text-yellow-300 transition duration-150" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-[0_0_15px_rgba(255,209,0,0.3)] text-sm font-bold tracking-wide text-tecsisa-dark bg-tecsisa-yellow hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-tecsisa-yellow focus:ring-offset-gray-900 transition duration-200 transform hover:scale-[1.02]">
                INGRESAR AL SISTEMA
            </button>
        </div>
    </form>
</x-guest-layout>
