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
        // ICD-10 Codes Reference Table
        Schema::create('icd10_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('description', 500);
            $table->string('category', 100)->nullable();
            $table->string('chapter', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['code']);
            $table->index(['description']);
        });

        // Clinical Templates (SOAP Templates)
        Schema::create('clinical_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('category', 50)->nullable()->comment('general, pediatric, dental, etc');
            $table->text('chief_complaint_template')->nullable();
            $table->text('history_template')->nullable();
            $table->text('examination_template')->nullable();
            $table->text('assessment_template')->nullable();
            $table->text('plan_template')->nullable();
            $table->json('vital_sign_defaults')->nullable();
            $table->json('common_diagnoses')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Encounters (Patient Visits Clinical Record)
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->string('encounter_no', 20)->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_visit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('clinical_templates')->nullOnDelete();
            $table->datetime('encounter_date');
            $table->enum('status', [
                'draft',          // Sedang ditulis
                'in_progress',    // Sedang rawatan
                'pending_review', // Menunggu semakan
                'completed',      // Selesai
                'cancelled',      // Dibatalkan
            ])->default('draft');

            // Chief Complaint
            $table->text('chief_complaint')->nullable();
            $table->text('history_present_illness')->nullable();

            // SOAP Notes
            $table->text('subjective')->nullable();
            $table->text('objective')->nullable();
            $table->text('assessment')->nullable();
            $table->text('plan')->nullable();

            // Additional Notes
            $table->text('clinical_notes')->nullable();
            $table->text('private_notes')->nullable()->comment('Doctor private notes');

            // Follow-up
            $table->date('follow_up_date')->nullable();
            $table->text('follow_up_instructions')->nullable();

            // Referral
            $table->boolean('needs_referral')->default(false);
            $table->string('referral_specialty', 100)->nullable();
            $table->text('referral_notes')->nullable();

            // Timestamps
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['encounter_date', 'status']);
            $table->index(['patient_id', 'encounter_date']);
        });

        // Vital Signs
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->datetime('recorded_at');

            // Basic Vitals
            $table->decimal('temperature', 4, 1)->nullable()->comment('Celsius');
            $table->integer('pulse_rate')->nullable()->comment('bpm');
            $table->integer('respiratory_rate')->nullable()->comment('breaths/min');
            $table->integer('systolic_bp')->nullable()->comment('mmHg');
            $table->integer('diastolic_bp')->nullable()->comment('mmHg');
            $table->integer('spo2')->nullable()->comment('Oxygen saturation %');

            // Additional Measurements
            $table->decimal('weight', 5, 2)->nullable()->comment('kg');
            $table->decimal('height', 5, 2)->nullable()->comment('cm');
            $table->decimal('bmi', 4, 1)->nullable();
            $table->decimal('blood_glucose', 5, 1)->nullable()->comment('mmol/L');

            // Pain Scale
            $table->integer('pain_score')->nullable()->comment('0-10 scale');
            $table->string('pain_location', 100)->nullable();

            // Notes
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['patient_id', 'recorded_at']);
        });

        // Diagnoses
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('icd10_id')->nullable()->constrained('icd10_codes')->nullOnDelete();
            $table->string('icd10_code', 10)->nullable();
            $table->string('diagnosis_text', 500);
            $table->enum('type', ['primary', 'secondary', 'provisional'])->default('primary');
            $table->enum('status', ['active', 'resolved', 'chronic'])->default('active');
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index(['icd10_code']);
        });

        // Clinical Notes (Additional notes beyond SOAP)
        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('note_type', [
                'progress',
                'procedure',
                'nursing',
                'consultation',
                'discharge',
                'other',
            ])->default('progress');
            $table->string('title', 200)->nullable();
            $table->text('content');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Patient Allergies
        Schema::create('patient_allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('allergen', 200);
            $table->enum('allergen_type', [
                'drug',
                'food',
                'environmental',
                'other',
            ])->default('drug');
            $table->enum('severity', [
                'mild',
                'moderate',
                'severe',
                'life_threatening',
            ])->default('moderate');
            $table->text('reaction')->nullable();
            $table->date('onset_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'resolved'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
        });

        // Medical History
        Schema::create('medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('history_type', [
                'past_medical',
                'surgical',
                'hospitalization',
                'chronic_disease',
                'immunization',
                'social',
                'obstetric',
            ]);
            $table->string('condition', 300);
            $table->date('onset_date')->nullable();
            $table->date('resolved_date')->nullable();
            $table->enum('status', ['active', 'resolved', 'ongoing'])->default('active');
            $table->text('details')->nullable();
            $table->text('treatment')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'history_type']);
        });

        // Family History
        Schema::create('family_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('relationship', 50)->comment('father, mother, sibling, etc');
            $table->string('condition', 300);
            $table->integer('age_at_onset')->nullable();
            $table->integer('age_at_death')->nullable();
            $table->enum('status', ['alive', 'deceased', 'unknown'])->default('unknown');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Clinical Attachments
        Schema::create('clinical_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->enum('attachment_type', [
                'lab_result',
                'imaging',
                'referral_letter',
                'consent_form',
                'prescription',
                'medical_certificate',
                'other',
            ]);
            $table->string('title', 200);
            $table->string('file_path', 500);
            $table->string('file_name', 200);
            $table->string('mime_type', 100);
            $table->integer('file_size');
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'attachment_type']);
        });

        // Referrals
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_no', 20)->unique();
            $table->foreignId('encounter_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referring_doctor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('referred_to', 200)->comment('Hospital/Clinic name');
            $table->string('department', 100)->nullable();
            $table->string('specialist_name', 200)->nullable();
            $table->enum('urgency', ['routine', 'urgent', 'emergency'])->default('routine');
            $table->text('reason_for_referral');
            $table->text('clinical_summary')->nullable();
            $table->text('relevant_investigations')->nullable();
            $table->text('current_medications')->nullable();
            $table->date('referral_date');
            $table->date('appointment_date')->nullable();
            $table->enum('status', [
                'pending',
                'sent',
                'acknowledged',
                'completed',
                'cancelled',
            ])->default('pending');
            $table->text('feedback')->nullable();
            $table->datetime('feedback_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'referral_date']);
        });

        // Procedures (Clinical procedures performed)
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('performed_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('procedure_code', 20)->nullable();
            $table->string('procedure_name', 200);
            $table->text('description')->nullable();
            $table->datetime('performed_at');
            $table->integer('duration_minutes')->nullable();
            $table->text('findings')->nullable();
            $table->text('complications')->nullable();
            $table->decimal('charge_amount', 12, 2)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'performed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('clinical_attachments');
        Schema::dropIfExists('family_histories');
        Schema::dropIfExists('medical_histories');
        Schema::dropIfExists('patient_allergies');
        Schema::dropIfExists('clinical_notes');
        Schema::dropIfExists('diagnoses');
        Schema::dropIfExists('vital_signs');
        Schema::dropIfExists('encounters');
        Schema::dropIfExists('clinical_templates');
        Schema::dropIfExists('icd10_codes');
    }
};
