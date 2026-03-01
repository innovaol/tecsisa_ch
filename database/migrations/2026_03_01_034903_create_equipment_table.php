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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('internal_id')->unique(); // ID Interno único, ej. CCTV-PB-04
            $table->string('name');
            $table->foreignId('system_id')->constrained('systems')->onDelete('cascade');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('status')->default('operative'); // operative, inoperative, partial
            $table->text('notes')->nullable();
            $table->json('specs')->nullable(); // Additional dynamic specs if needed without adding columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
