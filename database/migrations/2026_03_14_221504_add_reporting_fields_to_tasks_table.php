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
            $table->foreignId('system_id')->nullable()->after('equipment_id')->constrained('systems')->onDelete('set null');
            $table->boolean('is_additional')->default(false)->after('task_type'); // true if beyond initial scope
            $table->text('required_installations')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['system_id']);
            $table->dropColumn(['system_id', 'is_additional', 'required_installations']);
        });
    }
};
