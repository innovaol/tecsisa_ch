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
        Schema::table('equipment', function (Blueprint $table) {
            // Campos para Enlaces/Cables (Networking)
            $table->foreignId('source_equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->string('source_port')->nullable();
            
            $table->foreignId('destination_equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->string('destination_port')->nullable();

            // Metadatos de Certificación
            $table->string('certification_pdf')->nullable();
            $table->string('certification_status')->nullable(); // Pasa, Falla, Pendiente
            $table->date('certification_date')->nullable();
            
            // Atributos físicos del tramo
            $table->string('cable_category')->nullable(); // Cat 6, 6A, etc.
            $table->decimal('cable_length', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['source_equipment_id']);
            $table->dropForeign(['destination_equipment_id']);
            $table->dropColumn([
                'source_equipment_id', 'source_port', 
                'destination_equipment_id', 'destination_port',
                'certification_pdf', 'certification_status', 'certification_date',
                'cable_category', 'cable_length'
            ]);
        });
    }
};
