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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('max_shifts_per_week')->nullable()->after('hourly_rate');
            $table->integer('max_shifts_per_day')->nullable()->after('max_shifts_per_week');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['max_shifts_per_week', 'max_shifts_per_day']);
        });
    }
};
