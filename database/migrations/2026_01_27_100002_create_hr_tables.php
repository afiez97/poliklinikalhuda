<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Staff/Employee Records (extends users table)
        if (!Schema::hasTable('staff_profiles')) {
            Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employee_code', 20)->unique();
            $table->string('ic_number', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Employment Details
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->enum('employment_type', ['permanent', 'contract', 'part_time', 'locum', 'intern'])->default('permanent');
            $table->date('join_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->enum('employment_status', ['active', 'probation', 'resigned', 'terminated'])->default('active');

            // Salary & Bank
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();

            // Statutory Info
            $table->string('epf_number')->nullable();
            $table->string('socso_number')->nullable();
            $table->string('eis_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->decimal('epf_employee_rate', 5, 2)->default(11.00); // Default 11%
            $table->decimal('epf_employer_rate', 5, 2)->default(13.00); // Default 13%

            // Professional (for doctors)
            $table->string('mmc_number')->nullable();
            $table->string('apc_number')->nullable();
            $table->date('apc_expiry_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        }

        // Shift Types
        if (!Schema::hasTable('shift_types')) {
            Schema::create('shift_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('work_hours', 4, 2);
            $table->boolean('is_overnight')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }

        // Staff Schedules/Rosters
        if (!Schema::hasTable('staff_schedules')) {
            Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_type_id')->constrained('shift_types')->cascadeOnDelete();
            $table->date('schedule_date');
            $table->time('actual_start')->nullable();
            $table->time('actual_end')->nullable();
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'absent', 'on_leave'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'schedule_date']);
            $table->index('schedule_date');
        });
        }

        // Attendance Records
        if (!Schema::hasTable('attendance_records')) {
            Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('attendance_date');
            $table->datetime('clock_in')->nullable();
            $table->datetime('clock_out')->nullable();
            $table->string('clock_in_ip', 45)->nullable();
            $table->string('clock_out_ip', 45)->nullable();
            $table->decimal('clock_in_lat', 10, 8)->nullable();
            $table->decimal('clock_in_lng', 11, 8)->nullable();
            $table->decimal('clock_out_lat', 10, 8)->nullable();
            $table->decimal('clock_out_lng', 11, 8)->nullable();
            $table->enum('status', ['present', 'late', 'early_leave', 'absent', 'half_day', 'on_leave'])->default('present');
            $table->decimal('work_hours', 5, 2)->nullable();
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_adjusted')->default(false);
            $table->foreignId('adjusted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('adjustment_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'attendance_date']);
            $table->index('attendance_date');
        });
        }

        // Leave Types
        if (!Schema::hasTable('leave_types')) {
            Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->integer('default_days')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_attachment')->default(false);
            $table->boolean('is_carry_forward')->default(false);
            $table->integer('max_carry_forward_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        }

        // Leave Entitlements
        if (!Schema::hasTable('leave_entitlements')) {
            Schema::create('leave_entitlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->year('year');
            $table->decimal('entitled_days', 5, 2)->default(0);
            $table->decimal('used_days', 5, 2)->default(0);
            $table->decimal('pending_days', 5, 2)->default(0);
            $table->decimal('carried_forward', 5, 2)->default(0);
            $table->decimal('adjustment', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'leave_type_id', 'year']);
        });
        }

        // Leave Applications
        if (!Schema::hasTable('leave_applications')) {
            Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 30)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained('leave_types')->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 5, 2);
            $table->boolean('is_half_day')->default(false);
            $table->enum('half_day_type', ['morning', 'afternoon'])->nullable();
            $table->text('reason')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('approved_at')->nullable();
            $table->text('approver_remarks')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
        }

        // Payroll Periods
        if (!Schema::hasTable('payroll_periods')) {
            Schema::create('payroll_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_code', 20)->unique();
            $table->string('period_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('pay_date')->nullable();
            $table->enum('status', ['open', 'processing', 'finalized', 'paid'])->default('open');
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('processed_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('finalized_at')->nullable();
            $table->timestamps();
        });
        }

        // Payroll Records
        if (!Schema::hasTable('payroll_records')) {
            Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Earnings
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('overtime_pay', 10, 2)->default(0);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('other_earnings', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2)->default(0);

            // Deductions
            $table->decimal('epf_employee', 10, 2)->default(0);
            $table->decimal('epf_employer', 10, 2)->default(0);
            $table->decimal('socso_employee', 10, 2)->default(0);
            $table->decimal('socso_employer', 10, 2)->default(0);
            $table->decimal('eis_employee', 10, 2)->default(0);
            $table->decimal('eis_employer', 10, 2)->default(0);
            $table->decimal('pcb', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);

            // Net
            $table->decimal('net_salary', 10, 2)->default(0);

            // Work details
            $table->integer('work_days')->default(0);
            $table->decimal('work_hours', 6, 2)->default(0);
            $table->decimal('overtime_hours', 6, 2)->default(0);
            $table->integer('leave_days')->default(0);
            $table->integer('absent_days')->default(0);

            $table->json('earnings_breakdown')->nullable();
            $table->json('deductions_breakdown')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['payroll_period_id', 'user_id']);
        });
        }

        // Allowance Types
        if (!Schema::hasTable('allowance_types')) {
            Schema::create('allowance_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('type', ['fixed', 'variable'])->default('fixed');
            $table->decimal('default_amount', 10, 2)->default(0);
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        }

        // Staff Allowances
        if (!Schema::hasTable('staff_allowances')) {
            Schema::create('staff_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('allowance_type_id')->constrained('allowance_types')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
        }

        // Doctor Commission Config
        if (!Schema::hasTable('doctor_commissions')) {
            Schema::create('doctor_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('calculation_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('rate', 10, 2); // percentage or fixed amount
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
        });
        }

        // Public Holidays
        if (!Schema::hasTable('public_holidays')) {
            Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date');
            $table->string('name');
            $table->string('state')->nullable(); // null = nationwide
            $table->year('year');
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();

            $table->unique(['holiday_date', 'state']);
            $table->index(['year', 'state']);
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('public_holidays');
        Schema::dropIfExists('doctor_commissions');
        Schema::dropIfExists('staff_allowances');
        Schema::dropIfExists('allowance_types');
        Schema::dropIfExists('payroll_records');
        Schema::dropIfExists('payroll_periods');
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('leave_entitlements');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('staff_schedules');
        Schema::dropIfExists('shift_types');
        Schema::dropIfExists('staff_profiles');
    }
};
