<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Symptom library
        if (!Schema::hasTable('symptoms')) {
            Schema::create('symptoms', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique();
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->string('body_region')->nullable(); // head, chest, abdomen, limbs, etc.
                $table->string('category')->nullable(); // pain, fever, respiratory, gastrointestinal, etc.
                $table->boolean('is_red_flag')->default(false);
                $table->text('red_flag_conditions')->nullable(); // JSON: conditions that make this a red flag
                $table->text('associated_diagnoses')->nullable(); // JSON: common diagnoses
                $table->text('follow_up_questions')->nullable(); // JSON: questions to ask
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['body_region', 'category']);
                $table->index('is_red_flag');
            });
        }

        // Triage assessments
        if (!Schema::hasTable('triage_assessments')) {
            Schema::create('triage_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
                $table->foreignId('queue_id')->nullable()->constrained('queues')->nullOnDelete();
                $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
                $table->string('chief_complaint');
                $table->text('symptoms_data'); // JSON: detailed symptoms input
                $table->json('vital_signs')->nullable(); // BP, HR, Temp, RR, SpO2
                $table->string('pain_score', 2)->nullable(); // 0-10
                $table->string('pain_location')->nullable();
                $table->text('additional_notes')->nullable();

                // AI-generated fields
                $table->enum('severity_level', ['emergency', 'urgent', 'semi_urgent', 'standard', 'non_urgent'])->default('standard');
                $table->integer('severity_score')->default(50); // 0-100
                $table->text('ai_reasoning')->nullable(); // JSON: explanation of severity
                $table->json('red_flags_detected')->nullable();
                $table->json('differential_diagnoses')->nullable(); // AI suggested diagnoses with confidence
                $table->json('recommended_actions')->nullable();
                $table->integer('ai_confidence')->default(0); // 0-100%

                // Human review
                $table->enum('override_level', ['emergency', 'urgent', 'semi_urgent', 'standard', 'non_urgent'])->nullable();
                $table->text('override_reason')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->enum('status', ['pending', 'reviewed', 'completed'])->default('pending');

                $table->timestamps();

                $table->index(['patient_id', 'created_at']);
                $table->index(['severity_level', 'status']);
            });
        }

        // AI feedback for learning
        if (!Schema::hasTable('ai_feedbacks')) {
            Schema::create('ai_feedbacks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('triage_assessment_id')->nullable()->constrained()->nullOnDelete();
                $table->morphs('feedbackable'); // Can be triage, diagnosis suggestion, etc.
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('feedback_type', ['accept', 'reject', 'modify']);
                $table->text('ai_suggestion'); // What AI suggested
                $table->text('user_decision')->nullable(); // What user decided
                $table->text('reason')->nullable();
                $table->integer('ai_confidence')->nullable();
                $table->boolean('was_helpful')->nullable();
                $table->timestamps();

                $table->index(['feedback_type', 'created_at']);
            });
        }

        // Drug interactions database
        if (!Schema::hasTable('drug_interactions')) {
            Schema::create('drug_interactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('drug_a_id')->constrained('medicines')->cascadeOnDelete();
                $table->foreignId('drug_b_id')->constrained('medicines')->cascadeOnDelete();
                $table->enum('severity', ['severe', 'moderate', 'mild'])->default('moderate');
                $table->text('description');
                $table->text('mechanism')->nullable();
                $table->text('management')->nullable();
                $table->text('clinical_effects')->nullable();
                $table->string('source')->nullable(); // Reference source
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['drug_a_id', 'drug_b_id']);
                $table->index('severity');
            });
        }

        // Clinical guidelines reference
        if (!Schema::hasTable('clinical_guidelines')) {
            Schema::create('clinical_guidelines', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('title');
                $table->string('category'); // diagnosis, treatment, prevention
                $table->string('condition')->nullable(); // diabetes, hypertension, etc.
                $table->text('summary');
                $table->text('recommendations')->nullable(); // JSON
                $table->string('source')->nullable(); // MOH, WHO, etc.
                $table->string('version')->nullable();
                $table->date('effective_date')->nullable();
                $table->string('url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['category', 'condition']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_guidelines');
        Schema::dropIfExists('drug_interactions');
        Schema::dropIfExists('ai_feedbacks');
        Schema::dropIfExists('triage_assessments');
        Schema::dropIfExists('symptoms');
    }
};
