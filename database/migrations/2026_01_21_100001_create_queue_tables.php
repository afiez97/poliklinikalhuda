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
        // Queue Types (Pendaftaran, Doktor 1, Farmasi, etc.)
        Schema::create('queue_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Queue prefix (R, D1, F)');
            $table->string('name', 100)->comment('Queue name in BM');
            $table->string('name_en', 100)->nullable()->comment('English name');
            $table->string('name_zh', 100)->nullable()->comment('Chinese name');
            $table->integer('avg_service_time')->default(5)->comment('Average minutes per service');
            $table->integer('max_queue_size')->nullable()->comment('Max tickets per day');
            $table->integer('priority_ratio')->default(3)->comment('Every N normal, call 1 priority');
            $table->foreignId('auto_transfer_to')->nullable()->constrained('queue_types')->nullOnDelete();
            $table->time('operating_start')->nullable();
            $table->time('operating_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });

        // Queue Counters/Rooms
        Schema::create('queue_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_type_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20)->comment('Counter code (K1, K2, BD1)');
            $table->string('name', 100)->comment('Counter name in BM');
            $table->string('name_en', 100)->nullable();
            $table->string('name_zh', 100)->nullable();
            $table->string('location', 255)->nullable()->comment('Physical location');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['queue_type_id', 'code']);
        });

        // Queue Tickets
        Schema::create('queue_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20)->comment('Full number (D1-015)');
            $table->integer('sequence')->comment('Daily sequence (15)');
            $table->foreignId('queue_type_id')->constrained()->cascadeOnDelete();
            $table->date('queue_date');
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_visit_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('priority_level')->default(6)->comment('1=Emergency to 6=Normal');
            $table->string('priority_reason', 100)->nullable()->comment('OKU, Warga Emas, etc');
            $table->enum('status', [
                'waiting',
                'called',
                'serving',
                'completed',
                'no_show',
                'cancelled',
                'on_hold',
                'transferred',
            ])->default('waiting');
            $table->foreignId('current_counter_id')->nullable()->constrained('queue_counters')->nullOnDelete();
            $table->foreignId('served_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('serving_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->tinyInteger('call_count')->default(0)->comment('Number of times called');
            $table->integer('estimated_wait_time')->nullable()->comment('EWT in minutes at issue time');
            $table->integer('actual_wait_time')->nullable()->comment('Actual wait in minutes');
            $table->integer('service_time')->nullable()->comment('Service time in minutes');
            $table->enum('source', ['kiosk', 'counter', 'auto', 'mobile'])->default('counter');
            $table->foreignId('parent_ticket_id')->nullable()->constrained('queue_tickets')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['queue_type_id', 'queue_date', 'sequence']);
            $table->index(['status', 'queue_date']);
            $table->index(['patient_id']);
            $table->index(['queue_date', 'queue_type_id', 'status']);
        });

        // Queue Calls (Call history per ticket)
        Schema::create('queue_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('queue_tickets')->cascadeOnDelete();
            $table->foreignId('counter_id')->constrained('queue_counters')->cascadeOnDelete();
            $table->foreignId('called_by')->constrained('users')->cascadeOnDelete();
            $table->enum('call_type', ['initial', 'recall'])->default('initial');
            $table->timestamp('called_at');
            $table->boolean('responded')->default(false);
            $table->timestamps();

            $table->index(['ticket_id', 'called_at']);
        });

        // Queue Transfers
        Schema::create('queue_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_ticket_id')->constrained('queue_tickets')->cascadeOnDelete();
            $table->foreignId('to_ticket_id')->constrained('queue_tickets')->cascadeOnDelete();
            $table->foreignId('from_queue_type_id')->constrained('queue_types')->cascadeOnDelete();
            $table->foreignId('to_queue_type_id')->constrained('queue_types')->cascadeOnDelete();
            $table->enum('transfer_type', ['auto', 'manual'])->default('auto');
            $table->string('reason', 255)->nullable();
            $table->foreignId('transferred_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('transferred_at');
            $table->timestamps();
        });

        // Queue Staff Assignments
        Schema::create('queue_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('counter_id')->constrained('queue_counters')->cascadeOnDelete();
            $table->date('assignment_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'counter_id', 'assignment_date'], 'staff_assignment_unique');
            $table->index(['assignment_date', 'is_active']);
        });

        // Queue Notifications
        Schema::create('queue_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('queue_tickets')->cascadeOnDelete();
            $table->enum('notification_type', [
                'issued',
                'approaching',
                'called',
                'no_show_warning',
                'transferred',
            ]);
            $table->enum('channel', ['sms', 'whatsapp'])->default('sms');
            $table->string('recipient', 50);
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['ticket_id', 'notification_type']);
        });

        // Queue Daily Stats (Aggregated)
        Schema::create('queue_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_type_id')->constrained()->cascadeOnDelete();
            $table->date('stat_date');
            $table->integer('total_tickets')->default(0);
            $table->integer('served_tickets')->default(0);
            $table->integer('no_show_tickets')->default(0);
            $table->integer('cancelled_tickets')->default(0);
            $table->integer('avg_wait_time')->nullable()->comment('Average wait time in minutes');
            $table->integer('max_wait_time')->nullable();
            $table->integer('min_wait_time')->nullable();
            $table->integer('avg_service_time')->nullable();
            $table->time('peak_hour_start')->nullable();
            $table->time('peak_hour_end')->nullable();
            $table->integer('peak_hour_tickets')->nullable();
            $table->timestamps();

            $table->unique(['queue_type_id', 'stat_date']);
        });

        // Queue Hourly Stats
        Schema::create('queue_hourly_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_type_id')->constrained()->cascadeOnDelete();
            $table->date('stat_date');
            $table->tinyInteger('stat_hour')->comment('0-23');
            $table->integer('tickets_issued')->default(0);
            $table->integer('tickets_served')->default(0);
            $table->integer('avg_wait_time')->nullable();
            $table->integer('active_counters')->default(0);
            $table->timestamps();

            $table->unique(['queue_type_id', 'stat_date', 'stat_hour']);
        });

        // Queue Kiosks
        Schema::create('queue_kiosks', function (Blueprint $table) {
            $table->id();
            $table->string('kiosk_id', 20)->unique();
            $table->string('name', 100);
            $table->string('location', 255)->nullable();
            $table->enum('status', ['online', 'offline', 'maintenance'])->default('offline');
            $table->boolean('is_active')->default(true);
            $table->json('available_queue_types')->nullable()->comment('Array of queue_type_ids');
            $table->timestamp('last_heartbeat')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_kiosks');
        Schema::dropIfExists('queue_hourly_stats');
        Schema::dropIfExists('queue_daily_stats');
        Schema::dropIfExists('queue_notifications');
        Schema::dropIfExists('queue_staff_assignments');
        Schema::dropIfExists('queue_transfers');
        Schema::dropIfExists('queue_calls');
        Schema::dropIfExists('queue_tickets');
        Schema::dropIfExists('queue_counters');
        Schema::dropIfExists('queue_types');
    }
};
