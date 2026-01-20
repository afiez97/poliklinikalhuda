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
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();

            // Basic Salary
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->unsignedSmallInteger('working_days')->default(0);
            $table->unsignedSmallInteger('days_worked')->default(0);

            // Earnings
            $table->decimal('overtime_pay', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('other_earnings', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);

            // Deductions - Statutory
            $table->decimal('epf_employee', 12, 2)->default(0)->comment('KWSP pekerja');
            $table->decimal('epf_employer', 12, 2)->default(0)->comment('KWSP majikan');
            $table->decimal('socso_employee', 12, 2)->default(0)->comment('PERKESO pekerja');
            $table->decimal('socso_employer', 12, 2)->default(0)->comment('PERKESO majikan');
            $table->decimal('eis_employee', 12, 2)->default(0)->comment('SIP pekerja');
            $table->decimal('eis_employer', 12, 2)->default(0)->comment('SIP majikan');
            $table->decimal('pcb', 12, 2)->default(0)->comment('Potongan Cukai Bulanan');

            // Other Deductions
            $table->decimal('unpaid_leave_deduction', 12, 2)->default(0);
            $table->decimal('loan_deduction', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);

            // Net Pay
            $table->decimal('net_salary', 12, 2)->default(0);

            // Payment Info
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->datetime('paid_at')->nullable();
            $table->string('payment_reference')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['payroll_period_id', 'staff_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};
