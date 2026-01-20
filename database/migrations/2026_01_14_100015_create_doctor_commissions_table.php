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
        Schema::create('doctor_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete();
            $table->string('commission_type', 50)->comment('consultation, procedure, panel, etc');
            $table->enum('calculation_type', ['percentage', 'fixed']);
            $table->decimal('rate', 8, 2)->comment('Percentage or fixed amount');
            $table->decimal('min_amount', 12, 2)->nullable()->comment('Minimum commission per item');
            $table->decimal('max_amount', 12, 2)->nullable()->comment('Maximum commission per item');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['staff_id', 'commission_type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_commissions');
    }
};
