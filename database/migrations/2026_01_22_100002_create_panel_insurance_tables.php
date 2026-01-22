<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. panels - Panel master data
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->string('panel_code', 50)->unique();
            $table->string('panel_name');
            $table->enum('panel_type', ['corporate', 'insurance', 'government'])->default('corporate');
            $table->string('contact_person')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->integer('payment_terms_days')->default(30);
            $table->integer('sla_approval_days')->default(7);
            $table->integer('sla_payment_days')->default(14);
            $table->string('logo_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. panel_contracts - Contract management
        Schema::create('panel_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number', 100)->nullable();
            $table->date('effective_date');
            $table->date('expiry_date');
            $table->date('renewal_date')->nullable();
            $table->string('document_path')->nullable();
            $table->decimal('annual_cap', 15, 2)->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. panel_packages - Coverage packages per panel
        Schema::create('panel_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->string('package_code', 50);
            $table->string('package_name');
            $table->text('description')->nullable();
            $table->decimal('annual_limit', 15, 2)->nullable();
            $table->decimal('per_visit_limit', 15, 2)->nullable();
            $table->decimal('consultation_limit', 15, 2)->nullable();
            $table->decimal('medication_limit', 15, 2)->nullable();
            $table->decimal('procedure_limit', 15, 2)->nullable();
            $table->decimal('lab_limit', 15, 2)->nullable();
            $table->decimal('co_payment_percentage', 5, 2)->default(0);
            $table->decimal('deductible_amount', 10, 2)->default(0);
            $table->enum('deductible_type', ['per_visit', 'per_year'])->default('per_visit');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['panel_id', 'package_code']);
        });

        // 4. panel_fee_schedules - Fee rates per panel
        Schema::create('panel_fee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_type', 50); // consultation, procedure, medication, lab
            $table->string('service_code', 50)->nullable();
            $table->string('service_name');
            $table->decimal('panel_rate', 10, 2);
            $table->decimal('standard_rate', 10, 2)->nullable();
            $table->decimal('markup_percentage', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['panel_id', 'service_type', 'service_code']);
        });

        // 5. panel_exclusions - Excluded items per panel
        Schema::create('panel_exclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_package_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('exclusion_type', ['procedure', 'medication', 'diagnosis', 'category']);
            $table->string('exclusion_code', 50)->nullable();
            $table->string('exclusion_name');
            $table->text('reason')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['panel_id', 'exclusion_type']);
        });

        // 6. panel_employees - Employee master (principal cardholder)
        Schema::create('panel_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('employee_id', 100); // Staff ID
            $table->string('name');
            $table->string('ic_number', 20)->nullable();
            $table->string('passport_number', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();
            $table->foreignId('package_id')->nullable()->constrained('panel_packages')->nullOnDelete();
            $table->date('join_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['panel_id', 'employee_id']);
            $table->index('ic_number');
        });

        // 7. panel_dependents - Dependents linked to employees
        Schema::create('panel_dependents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('name');
            $table->string('ic_number', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('relationship', ['spouse', 'child', 'parent', 'sibling', 'other'])->default('spouse');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->boolean('has_separate_limit')->default(false);
            $table->decimal('separate_limit', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('ic_number');
        });

        // 8. guarantee_letters - GL records
        Schema::create('guarantee_letters', function (Blueprint $table) {
            $table->id();
            $table->string('gl_number', 100)->unique();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('panel_dependent_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_path')->nullable();
            $table->decimal('coverage_limit', 15, 2);
            $table->decimal('amount_used', 15, 2)->default(0);
            $table->decimal('amount_balance', 15, 2)->default(0);
            $table->date('effective_date');
            $table->date('expiry_date');
            $table->text('diagnoses_covered')->nullable();
            $table->text('special_remarks')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected', 'expired'])->default('pending');
            $table->enum('verification_method', ['system', 'phone', 'email', 'portal'])->nullable();
            $table->string('verification_person')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->enum('status', ['active', 'utilized', 'expired', 'cancelled'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['panel_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index('expiry_date');
        });

        // 9. gl_utilizations - GL usage tracking
        Schema::create('gl_utilizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guarantee_letter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            $table->date('utilization_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('running_balance', 15, 2);
            $table->string('reference_type', 50)->nullable(); // invoice, adjustment, refund
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 10. benefit_limit_trackings - Real-time limit usage
        Schema::create('benefit_limit_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('panel_dependent_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_package_id')->nullable()->constrained()->nullOnDelete();
            $table->year('benefit_year');
            $table->decimal('annual_limit', 15, 2)->default(0);
            $table->decimal('annual_used', 15, 2)->default(0);
            $table->decimal('annual_balance', 15, 2)->default(0);
            $table->decimal('consultation_used', 15, 2)->default(0);
            $table->decimal('medication_used', 15, 2)->default(0);
            $table->decimal('procedure_used', 15, 2)->default(0);
            $table->decimal('lab_used', 15, 2)->default(0);
            $table->integer('visit_count')->default(0);
            $table->date('last_visit_date')->nullable();
            $table->timestamps();

            $table->unique(['panel_id', 'patient_id', 'benefit_year']);
        });

        // 11. panel_eligibility_checks - Verification logs
        Schema::create('panel_eligibility_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_employee_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('guarantee_letter_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('check_date');
            $table->enum('check_method', ['system', 'phone', 'email', 'portal'])->default('system');
            $table->string('verifier_name')->nullable();
            $table->boolean('is_eligible')->default(false);
            $table->text('eligibility_details')->nullable();
            $table->decimal('available_limit', 15, 2)->nullable();
            $table->text('coverage_info')->nullable();
            $table->text('exclusions_info')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 12. pre_authorizations - PA requests and approvals
        Schema::create('pre_authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('pa_number', 50)->unique();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guarantee_letter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            $table->string('procedure_code', 50)->nullable();
            $table->string('procedure_name');
            $table->decimal('estimated_cost', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->string('icd10_primary', 10)->nullable();
            $table->text('icd10_secondary')->nullable();
            $table->text('clinical_justification')->nullable();
            $table->text('supporting_documents')->nullable(); // JSON array of file paths
            $table->date('requested_date');
            $table->date('procedure_date')->nullable();
            $table->string('approval_number', 100)->nullable();
            $table->date('approval_expiry')->nullable();
            $table->enum('status', ['draft', 'submitted', 'pending', 'approved', 'rejected', 'expired', 'cancelled'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('panel_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['panel_id', 'status']);
            $table->index(['patient_id', 'status']);
        });

        // 13. panel_claims - Claim submissions
        Schema::create('panel_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number', 50)->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guarantee_letter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pre_authorization_id')->nullable()->constrained()->nullOnDelete();
            $table->date('claim_date');
            $table->date('service_date');
            $table->string('icd10_primary', 10);
            $table->text('icd10_secondary')->nullable(); // JSON
            $table->decimal('total_invoice_amount', 15, 2);
            $table->decimal('co_payment_amount', 15, 2)->default(0);
            $table->decimal('deductible_amount', 15, 2)->default(0);
            $table->decimal('excluded_amount', 15, 2)->default(0);
            $table->decimal('claimable_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2)->nullable();
            $table->decimal('adjustment_amount', 15, 2)->default(0);
            $table->enum('claim_status', [
                'draft', 'submitted', 'acknowledged', 'under_review',
                'approved', 'partially_approved', 'rejected', 'paid', 'cancelled'
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->text('panel_remarks')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->date('sla_due_date')->nullable();
            $table->boolean('is_overdue')->default(false);
            $table->string('batch_id', 50)->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['panel_id', 'claim_status']);
            $table->index(['patient_id', 'claim_status']);
            $table->index('sla_due_date');
            $table->index('batch_id');
        });

        // 14. claim_documents - Attached documents per claim
        Schema::create('claim_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_claim_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', [
                'gl_copy', 'invoice', 'medical_certificate', 'lab_report',
                'prescription', 'pa_approval', 'referral_letter', 'other'
            ]);
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_type', 50)->nullable();
            $table->integer('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 15. claim_rejections - Rejection records
        Schema::create('claim_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_claim_id')->constrained()->cascadeOnDelete();
            $table->date('rejection_date');
            $table->string('rejection_code', 50)->nullable();
            $table->text('rejection_reason');
            $table->decimal('rejected_amount', 15, 2);
            $table->decimal('partial_approved_amount', 15, 2)->nullable();
            $table->text('panel_remarks')->nullable();
            $table->boolean('is_appealable')->default(true);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 16. claim_appeals - Appeal records
        Schema::create('claim_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_claim_id')->constrained()->cascadeOnDelete();
            $table->foreignId('claim_rejection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('appeal_number', 50)->nullable();
            $table->date('appeal_date');
            $table->text('appeal_reason');
            $table->text('additional_documents')->nullable(); // JSON array of file paths
            $table->text('supporting_notes')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'withdrawn'])->default('submitted');
            $table->decimal('original_amount', 15, 2);
            $table->decimal('appealed_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->text('panel_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 17. payment_advices - Panel payment records
        Schema::create('payment_advices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->string('advice_number', 100)->nullable();
            $table->date('advice_date');
            $table->date('payment_date')->nullable();
            $table->string('payment_reference', 100)->nullable();
            $table->string('payment_method', 50)->nullable(); // cheque, bank_transfer, etc
            $table->decimal('total_amount', 15, 2);
            $table->integer('claim_count')->default(0);
            $table->string('file_path')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // 18. payment_reconciliations - Reconciliation matching
        Schema::create('payment_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_advice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('panel_claim_id')->constrained()->cascadeOnDelete();
            $table->decimal('claimed_amount', 15, 2);
            $table->decimal('approved_amount', 15, 2)->nullable();
            $table->decimal('paid_amount', 15, 2);
            $table->decimal('adjustment_amount', 15, 2)->default(0);
            $table->decimal('discrepancy_amount', 15, 2)->default(0);
            $table->enum('match_status', ['matched', 'short_payment', 'over_payment', 'unmatched'])->default('matched');
            $table->text('discrepancy_reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->foreignId('reconciled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_reconciliations');
        Schema::dropIfExists('payment_advices');
        Schema::dropIfExists('claim_appeals');
        Schema::dropIfExists('claim_rejections');
        Schema::dropIfExists('claim_documents');
        Schema::dropIfExists('panel_claims');
        Schema::dropIfExists('pre_authorizations');
        Schema::dropIfExists('panel_eligibility_checks');
        Schema::dropIfExists('benefit_limit_trackings');
        Schema::dropIfExists('gl_utilizations');
        Schema::dropIfExists('guarantee_letters');
        Schema::dropIfExists('panel_dependents');
        Schema::dropIfExists('panel_employees');
        Schema::dropIfExists('panel_exclusions');
        Schema::dropIfExists('panel_fee_schedules');
        Schema::dropIfExists('panel_packages');
        Schema::dropIfExists('panel_contracts');
        Schema::dropIfExists('panels');
    }
};
