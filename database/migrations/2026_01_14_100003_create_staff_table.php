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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('staff_no', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete();

            // Personal Information
            $table->string('name', 255);
            $table->string('ic_no', 20)->unique()->comment('MyKad number');
            $table->enum('gender', ['male', 'female']);
            $table->date('date_of_birth');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->default('single');
            $table->string('nationality', 50)->default('Malaysia');
            $table->string('race', 50)->nullable();
            $table->string('religion', 50)->nullable();

            // Contact Information
            $table->string('phone', 20)->nullable();
            $table->string('phone_emergency', 20)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_relation', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();

            // Employment Information
            $table->enum('employment_type', ['tetap', 'kontrak', 'part_time', 'locum'])->default('tetap');
            $table->date('join_date');
            $table->date('confirmation_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('last_working_date')->nullable();
            $table->enum('status', ['active', 'resigned', 'terminated', 'on_leave'])->default('active');

            // Payroll Information
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->string('epf_no', 20)->nullable();
            $table->string('socso_no', 20)->nullable();
            $table->string('eis_no', 20)->nullable();
            $table->string('income_tax_no', 20)->nullable();

            // Professional Information (for doctors/nurses)
            $table->string('mmc_no', 20)->nullable()->comment('Malaysian Medical Council number');
            $table->string('apc_no', 20)->nullable()->comment('Annual Practicing Certificate');
            $table->date('apc_expiry_date')->nullable();
            $table->string('specialty', 100)->nullable();

            // Photo
            $table->string('photo')->nullable();

            // Notes
            $table->text('notes')->nullable();

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['department_id', 'status']);
            $table->index(['position_id', 'status']);
            $table->index('employment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
