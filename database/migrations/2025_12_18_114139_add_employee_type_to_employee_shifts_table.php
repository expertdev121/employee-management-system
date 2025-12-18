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
        Schema::table('employee_shifts', function (Blueprint $table) {
            $table->string('employee_type')->default('App\\Models\\User')->after('employee_id');
            $table->index(['employee_id', 'employee_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_shifts', function (Blueprint $table) {
            //
        });
    }
};
