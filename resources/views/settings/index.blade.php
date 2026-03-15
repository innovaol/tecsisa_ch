<x-app-layout>
    <x-slot name="header">
        <!-- Header: Tarjeta Propia -->
        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-8 mb-6 transition-all duration-500 shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-tecsisa-yellow/5 rounded-full blur-2xl"></div>
            <h2 class="text-3xl font-black transition-colors duration-500 leading-tight" :class="theme === 'light' ? 'text-slate-800' : 'text-white'">
                Identidad <span class="text-tecsisa-yellow uppercase tracking-widest text-sm font-black">Corporativa</span>
            </h2>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-2 px-1">Configuración global del nombre y logotipo de la empresa para la plataforma y reportes</p>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-500 text-xs font-bold uppercase tracking-widest flex items-center gap-3 animate-pulse">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-theme-card border border-theme rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden transition-all duration-500">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-tecsisa-yellow/5 rounded-full blur-[100px] pointer-events-none"></div>

            <form method="post" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="relative z-10 space-y-10">
                @csrf
                @method('patch')

                <!-- Logo Section -->
                <div class="flex flex-col md:flex-row items-center gap-10 border-b border-theme pb-10">
                    <div class="relative group">
                        <div class="w-40 h-40 rounded-[2.5rem] bg-black/20 flex items-center justify-center border-2 border-dashed border-theme group-hover:border-tecsisa-yellow transition-all duration-500 overflow-hidden shadow-inner">
                            @if($settings['company_logo'])
                                <img src="{{ asset('storage/' . $settings['company_logo']) }}" id="logo-preview" class="w-full h-full object-contain p-4 transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div id="logo-placeholder" class="text-gray-600 flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[8px] font-black uppercase tracking-widest">Sin Logo</span>
                                </div>
                            @endif
                        </div>
                        <label for="company_logo" class="absolute -bottom-2 -right-2 w-10 h-10 bg-tecsisa-yellow rounded-2xl flex items-center justify-center text-black cursor-pointer shadow-xl hover:scale-110 active:scale-95 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </label>
                        <input type="file" name="company_logo" id="company_logo" class="hidden" accept="image/*" @change="previewImage($event)">
                    </div>

                    <div class="flex-1 text-center md:text-left space-y-2">
                        <h4 class="text-sm font-black text-tecsisa-yellow uppercase tracking-widest">Imagotipo Oficial</h4>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider leading-relaxed">
                            Cargue el logotipo de su empresa. Se recomienda un archivo PNG con fondo transparente. 
                            Este aparecerá en la barra lateral y en el encabezado de los reportes PDF generados.
                        </p>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre Comercial de la Empresa</label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" 
                               class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-14 px-6 font-bold shadow-inner" 
                               placeholder="Ej: TECSISA S.A.S">
                        @error('company_name') <p class="text-red-500 text-[10px] font-bold uppercase mt-1 px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-4">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Pie de Página (Slogan / Legal)</label>
                        <textarea name="company_footer" class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition p-6 font-bold shadow-inner h-32" 
                                  placeholder="Ej: © {{ date('Y') }} - Soluciones de Infraestructura - Ciudad Hospitalaria">{{ old('company_footer', $settings['company_footer']) }}</textarea>
                    </div>
                </div>

                <!-- Separador -->
                <div class="border-t border-theme pt-8">
                    <h4 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Ingeniero Responsable (Reporte PDF)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre Completo</label>
                            <input type="text" name="engineer_name" value="{{ old('engineer_name', $settings['engineer_name']) }}"
                                   class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-5 font-bold shadow-inner"
                                   placeholder="Ej: Luis Gálvez">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Cargo</label>
                            <input type="text" name="engineer_cargo" value="{{ old('engineer_cargo', $settings['engineer_cargo']) }}"
                                   class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-5 font-bold shadow-inner"
                                   placeholder="Ej: Ing. Supervisor de Obra">
                        </div>
                    </div>
                </div>

                <!-- Datos del Proyecto -->
                <div class="border-t border-theme pt-8">
                    <h4 class="text-[10px] font-black text-tecsisa-yellow uppercase tracking-widest mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Datos del Proyecto (encabezado de fotos en PDF)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">Nombre del Proyecto</label>
                            <input type="text" name="project_name" value="{{ old('project_name', $settings['project_name']) }}"
                                   class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-5 font-bold shadow-inner"
                                   placeholder="Ej: Hospital Anita Moreno — Sistemas Especiales">
                        </div>
                        <div class="space-y-3">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest px-1">N° de Contrato</label>
                            <input type="text" name="contract_number" value="{{ old('contract_number', $settings['contract_number']) }}"
                                   class="w-full bg-theme/5 border border-theme rounded-2xl text-theme focus:ring-2 focus:ring-tecsisa-yellow transition h-12 px-5 font-bold shadow-inner"
                                   placeholder="Ej: N° 183 (2010)">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6 border-t border-theme">
                    <button type="submit" class="bg-tecsisa-yellow hover:bg-yellow-400 text-black font-black px-10 py-4 rounded-3xl text-xs uppercase tracking-[0.2em] shadow-xl shadow-tecsisa-yellow/20 transition-all active:scale-95 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-black/20"></span>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('logo-preview');
                const placeholder = document.getElementById('logo-placeholder');
                
                if (output) {
                    output.src = reader.result;
                } else if (placeholder) {
                    // Create img if placeholder was there
                    const img = document.createElement('img');
                    img.id = 'logo-preview';
                    img.src = reader.result;
                    img.className = 'w-full h-full object-contain p-4 transition-transform duration-700 group-hover:scale-110';
                    placeholder.parentNode.appendChild(img);
                    placeholder.remove();
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>
