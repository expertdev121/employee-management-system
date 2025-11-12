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
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('employee_shift_id')->constrained('employee_shifts')->onDelete('cascade');
            $table->date('shift_date');
            $table->decimal('hourly_rate', 8, 2);
            $table->decimal('total_hours', 5, 2);
            $table->decimal('total_pay', 10, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('accepted_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'shift_date']);
            $table->index(['employee_shift_id']);
            $table->index('status');
            $table->unique(['employee_id', 'employee_shift_id', 'shift_date'], 'emp_payroll_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_payrolls');
    }
};
