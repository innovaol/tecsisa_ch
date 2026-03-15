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
            $table->boolean('has_new_cable')->default(false)->after('is_additional');
            $table->boolean('has_new_jack')->default(false)->after('has_new_cable');
            $table->boolean('has_new_faceplate')->default(false)->after('has_new_jack');
            $table->boolean('is_certified')->default(false)->after('has_new_faceplate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['has_new_cable', 'has_new_jack', 'has_new_faceplate', 'is_certified']);
        });
    }
};
