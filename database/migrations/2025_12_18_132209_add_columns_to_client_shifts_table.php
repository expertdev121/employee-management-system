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
        Schema::table('client_shifts', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('shift_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('client_shifts', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['client_id', 'shift_id', 'shift_date']);
        });
    }
};
