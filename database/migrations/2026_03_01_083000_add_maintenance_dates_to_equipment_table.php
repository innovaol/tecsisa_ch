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
            $table->date('installation_date')->nullable()->after('status');
            $table->date('last_maintenance_at')->nullable()->after('installation_date');
            $table->date('next_maintenance_at')->nullable()->after('last_maintenance_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['installation_date', 'last_maintenance_at', 'next_maintenance_at']);
        });
    }
};
