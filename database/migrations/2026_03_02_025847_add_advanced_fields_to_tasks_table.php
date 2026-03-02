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
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('initial_status')->nullable()->after('status');
            $table->string('final_status')->nullable()->after('initial_status');
            $table->timestamp('started_at')->nullable()->after('completed_at');
            $table->string('location_snapshot')->nullable()->after('equipment_id'); // E.g. "Edificio G > Piso 1 > Hemato Oncología"
            $table->text('admin_notes')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['initial_status', 'final_status', 'started_at', 'location_snapshot', 'admin_notes']);
        });
    }
};
