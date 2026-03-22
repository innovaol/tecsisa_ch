<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            // Relaciones de Red (Solo para sistemas tipo NET-LINK)
            $table->foreignId('source_equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->string('source_port')->nullable();
            
            $table->foreignId('destination_equipment_id')->nullable()->constrained('equipment')->onDelete('set null');
            $table->string('destination_port')->nullable();

            // Atributos base de Red (Fisicos)
            $table->integer('port_capacity')->nullable(); // Para Patch Panels y Switches
            $table->string('certification_pdf')->nullable(); // Para cables
            $table->string('certification_status')->nullable(); // Certified/Failed
            $table->date('certification_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropForeign(['source_equipment_id']);
            $table->dropForeign(['destination_equipment_id']);
            $table->dropColumn([
                'source_equipment_id', 'source_port', 
                'destination_equipment_id', 'destination_port',
                'port_capacity', 'certification_pdf', 
                'certification_status', 'certification_date'
            ]);
        });
    }
};
