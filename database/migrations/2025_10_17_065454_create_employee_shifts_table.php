<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('employee_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('shift_date');
            $table->enum('status', ['pending', 'assigned', 'accepted', 'rejected', 'cancelled'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'shift_date']);
            $table->index(['shift_id', 'shift_date']);
            $table->index('status');
            $table->unique(['employee_id', 'shift_id', 'shift_date']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('employee_shifts');
    }
};
