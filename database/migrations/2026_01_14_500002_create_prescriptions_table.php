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
        // Prescriptions Header
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('prescription_no', 20)->unique();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prescribed_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->datetime('prescription_date');
            $table->enum('status', [
                'draft',
                'pending',
                'dispensed',
                'partially_dispensed',
                'cancelled',
            ])->default('pending');

            // Validity
            $table->date('valid_until')->nullable();
            $table->integer('refill_count')->default(0);
            $table->integer('max_refills')->default(0);

            // Notes
            $table->text('clinical_notes')->nullable();
            $table->text('pharmacist_notes')->nullable();

            // Dispensing
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('dispensed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'prescription_date']);
            $table->index(['status']);
        });

        // Prescription Items
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('medicine_id')->nullable()->comment('Link to medicines when pharmacy module ready');
            $table->string('medicine_name', 200);
            $table->string('strength', 50)->nullable();
            $table->string('form', 50)->nullable()->comment('tablet, capsule, syrup, etc');

            // Dosage
            $table->string('dosage', 100)->comment('e.g., 500mg');
            $table->string('frequency', 100)->comment('e.g., TDS, BD, OD');
            $table->string('route', 50)->default('oral')->comment('oral, topical, IV, etc');
            $table->string('duration', 50)->nullable()->comment('e.g., 7 hari');
            $table->integer('quantity')->default(0);
            $table->string('unit', 20)->default('tablet');

            // Instructions
            $table->text('instructions')->nullable()->comment('Patient instructions');
            $table->text('special_instructions')->nullable();

            // Status
            $table->enum('status', ['pending', 'dispensed', 'cancelled', 'out_of_stock'])->default('pending');
            $table->integer('quantity_dispensed')->default(0);
            $table->datetime('dispensed_at')->nullable();

            // Pricing (for billing)
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
    }
};
