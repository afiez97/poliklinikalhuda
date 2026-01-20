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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_no', 20)->unique();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->date('appointment_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->default(15);

            // Type and priority
            $table->enum('appointment_type', ['consultation', 'follow_up', 'procedure', 'medical_checkup', 'vaccination', 'other'])->default('consultation');
            $table->enum('priority', ['normal', 'urgent'])->default('normal');

            // Status
            $table->enum('status', [
                'scheduled',     // Dijadualkan
                'confirmed',     // Disahkan
                'arrived',       // Telah tiba
                'in_progress',   // Sedang berlangsung
                'completed',     // Selesai
                'cancelled',     // Dibatalkan
                'no_show',       // Tidak hadir
                'rescheduled',   // Dijadualkan semula
            ])->default('scheduled');

            // Details
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();

            // Reminder
            $table->boolean('reminder_sent')->default(false);
            $table->datetime('reminder_sent_at')->nullable();
            $table->enum('reminder_type', ['sms', 'whatsapp', 'email'])->nullable();

            // Cancellation
            $table->text('cancellation_reason')->nullable();
            $table->datetime('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();

            // Rescheduling
            $table->foreignId('rescheduled_from')->nullable()->constrained('appointments')->nullOnDelete();

            // Panel
            $table->boolean('is_panel')->default(false);

            // Booking source
            $table->enum('booking_source', ['counter', 'phone', 'online', 'mobile_app'])->default('counter');

            // Metadata
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['appointment_date', 'doctor_id']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index(['status', 'appointment_date']);
        });

        // Add foreign key to patient_visits
        Schema::table('patient_visits', function (Blueprint $table) {
            $table->foreign('appointment_id')->references('id')->on('appointments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_visits', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
        });

        Schema::dropIfExists('appointments');
    }
};
