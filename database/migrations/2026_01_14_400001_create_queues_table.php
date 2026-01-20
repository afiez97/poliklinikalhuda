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
        // Queue counters for different departments/types
        Schema::create('queue_counters', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->enum('type', ['registration', 'consultation', 'pharmacy', 'payment', 'lab', 'other'])->default('consultation');
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Queue entries
        Schema::create('queue_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('queue_counter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_visit_id')->constrained()->cascadeOnDelete();
            $table->string('queue_number', 10);
            $table->enum('priority', ['normal', 'elderly', 'disabled', 'pregnant', 'urgent', 'emergency'])->default('normal');
            $table->integer('priority_weight')->default(0);
            $table->enum('status', [
                'waiting',      // Menunggu
                'calling',      // Sedang dipanggil
                'serving',      // Sedang dilayan
                'completed',    // Selesai
                'skipped',      // Dilangkau
                'transferred',  // Dipindahkan
                'cancelled',    // Dibatalkan
            ])->default('waiting');
            $table->foreignId('served_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('counter_number', 10)->nullable();
            $table->datetime('called_at')->nullable();
            $table->datetime('served_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->integer('wait_time_minutes')->nullable();
            $table->integer('serve_time_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['queue_counter_id', 'status']);
            $table->index(['created_at', 'status']);
        });

        // Queue display settings
        Schema::create('queue_displays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 100)->nullable();
            $table->json('counter_ids')->nullable()->comment('Which counters to display');
            $table->boolean('show_waiting')->default(true);
            $table->boolean('show_serving')->default(true);
            $table->integer('max_display')->default(10);
            $table->boolean('play_sound')->default(true);
            $table->string('sound_file')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_displays');
        Schema::dropIfExists('queue_entries');
        Schema::dropIfExists('queue_counters');
    }
};
