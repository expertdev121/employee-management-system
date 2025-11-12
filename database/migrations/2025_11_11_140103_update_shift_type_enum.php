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
        // First, drop the existing enum constraint
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('shift_type');
        });

        // Then add the new enum column
        Schema::table('shifts', function (Blueprint $table) {
            $table->enum('shift_type', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->default('monday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->enum('shift_type', ['morning', 'evening', 'night', 'custom'])->default('custom')->change();
        });
    }
};
