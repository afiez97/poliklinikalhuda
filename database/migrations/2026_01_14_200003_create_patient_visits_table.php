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
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visit_no', 20)->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('appointment_id')->nullable()->comment('Will be linked when appointments module is created');
            $table->date('visit_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('visit_type', ['walk_in', 'appointment', 'emergency', 'follow_up', 'referral'])->default('walk_in');
            $table->enum('priority', ['normal', 'urgent', 'emergency'])->default('normal');

            // Queue
            $table->integer('queue_number')->nullable();
            $table->string('queue_prefix', 5)->nullable();

            // Status tracking
            $table->enum('status', [
                'registered',     // Baru daftar
                'waiting',        // Menunggu
                'in_consultation', // Sedang jumpa doktor
                'pending_lab',    // Menunggu keputusan lab
                'pending_pharmacy', // Menunggu ubat
                'pending_payment', // Menunggu bayaran
                'completed',      // Selesai
                'cancelled',      // Dibatalkan
                'no_show',        // Tidak hadir
            ])->default('registered');

            // Assigned staff
            $table->foreignId('doctor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('staff')->nullOnDelete();

            // Consultation
            $table->text('chief_complaint')->nullable();
            $table->datetime('consultation_start')->nullable();
            $table->datetime('consultation_end')->nullable();

            // Billing
            $table->boolean('is_billable')->default(true);
            $table->boolean('is_panel')->default(false);
            $table->foreignId('panel_company_id')->nullable();

            // Metadata
            $table->foreignId('registered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['visit_date', 'status']);
            $table->index(['patient_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visits');
    }
};
