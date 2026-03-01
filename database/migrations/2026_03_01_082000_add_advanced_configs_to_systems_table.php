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
        Schema::table('systems', function (Blueprint $table) {
            $table->json('port_config')->nullable()->after('form_schema');
            $table->integer('maintenance_interval_days')->default(90)->after('port_config');
            $table->text('maintenance_guide')->nullable()->after('maintenance_interval_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn(['port_config', 'maintenance_interval_days', 'maintenance_guide']);
        });
    }
};
