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
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('port_a_id')->unique()->comment('Origin Port, one connection active per port');
            $table->unsignedBigInteger('port_b_id')->unique()->comment('Destination Port, one connection active per port');

            $table->string('cable_type')->nullable(); // cat6, mm_fiber, sm_fiber, dac
            $table->string('cable_color')->nullable(); // UI visualization (blue, red, yellow)
            $table->string('label')->nullable(); // Ex: CABLE-FO-01
            $table->text('notes')->nullable();

            $table->timestamps();

            // Setup foreign keys to ports table
            $table->foreign('port_a_id')->references('id')->on('ports')->onDelete('cascade');
            $table->foreign('port_b_id')->references('id')->on('ports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
