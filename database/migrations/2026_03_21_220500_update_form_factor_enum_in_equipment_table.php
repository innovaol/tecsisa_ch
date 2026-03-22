<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pasamos de un enum restrictivo a un string más flexible para soportar 'standalone'
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('form_factor', 50)->default('standalone')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->enum('form_factor', ['rackmount', 'peripheral', 'network_point'])->default('rackmount')->change();
        });
    }
};
