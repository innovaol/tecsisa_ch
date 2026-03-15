<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        // Añadir cédula al perfil del usuario (técnico)
        Schema::table('users', function (Blueprint $table) {
            $table->string('cedula')->nullable()->after('name');
            $table->string('cargo')->nullable()->after('cedula');
        });

        // Insertar settings del ingeniero responsable (si no existen)
        Setting::firstOrCreate(['key' => 'engineer_name'],  ['value' => '']);
        Setting::firstOrCreate(['key' => 'engineer_cargo'], ['value' => 'Ingeniero Responsable de Obra']);
        Setting::firstOrCreate(['key' => 'project_name'],   ['value' => 'Hospital Anita Moreno — Sistemas Especiales']);
        Setting::firstOrCreate(['key' => 'contract_number'],['value' => '']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cedula', 'cargo']);
        });
    }
};
