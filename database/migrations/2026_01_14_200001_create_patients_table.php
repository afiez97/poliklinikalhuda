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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn', 20)->unique()->comment('Medical Record Number');
            $table->string('ic_number', 20)->nullable()->index();
            $table->string('passport_number', 30)->nullable();
            $table->enum('id_type', ['ic', 'passport', 'military', 'police', 'birth_cert', 'other'])->default('ic');

            // Personal Information
            $table->string('name', 150);
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality', 50)->default('Malaysian');
            $table->string('race', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->string('occupation', 100)->nullable();

            // Contact Information
            $table->string('phone', 20)->nullable();
            $table->string('phone_alt', 20)->nullable();
            $table->string('email', 100)->nullable();

            // Address
            $table->text('address')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->default('Malaysia');

            // Emergency Contact
            $table->string('emergency_name', 100)->nullable();
            $table->string('emergency_phone', 20)->nullable();
            $table->string('emergency_relationship', 50)->nullable();

            // Medical Information
            $table->string('blood_type', 5)->nullable();
            $table->text('allergies')->nullable();
            $table->text('chronic_diseases')->nullable();
            $table->text('current_medications')->nullable();

            // Insurance/Panel
            $table->boolean('has_panel')->default(false);
            $table->string('panel_company', 100)->nullable();
            $table->string('panel_member_id', 50)->nullable();
            $table->date('panel_expiry_date')->nullable();

            // PDPA Consent
            $table->boolean('pdpa_consent')->default(false);
            $table->datetime('pdpa_consent_at')->nullable();
            $table->string('pdpa_consent_by', 100)->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'deceased', 'transferred'])->default('active');
            $table->text('notes')->nullable();

            // Metadata
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'phone']);
            $table->index('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
