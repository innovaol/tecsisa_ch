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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->string('number_label'); // e.g., "1", "01", "Fa0/1", "Eth1/1"
            $table->enum('port_type', ['rj45', 'lc', 'sc', 'st', 'sfp', 'sfp_plus', 'qsfp', 'coax', 'electrical', 'other'])->default('rj45');
            $table->enum('status', ['free', 'connected', 'broken', 'reserved', 'maintenance'])->default('free');
            $table->string('mac_address')->nullable();
            $table->string('vlan')->nullable(); // Tagging info
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
