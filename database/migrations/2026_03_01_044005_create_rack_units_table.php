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
        Schema::create('rack_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rack_id')->constrained('racks')->onDelete('cascade');
            $table->integer('unit_number'); // e.g., 1 to 42
            $table->string('side')->default('front'); // front or back

            // To prevent foreign key constraints error, we define equipment_id exactly as 'equipment' table's id
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('set null');

            $table->integer('position_size')->default(1); // Equipment size in U's (e.g. 1U, 2U, 4U)
            $table->string('content_type')->default('equipment'); // equipment, blank_panel, cable_management
            $table->timestamps();

            // Un rack no puede tener dos equipos inicializados en la misma unidad
            $table->unique(['rack_id', 'unit_number', 'side']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack_units');
    }
};
