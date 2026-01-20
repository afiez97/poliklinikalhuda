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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_record_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earning', 'deduction']);
            $table->string('code', 20);
            $table->string('description', 255);
            $table->decimal('amount', 12, 2);
            $table->unsignedSmallInteger('quantity')->default(1)->comment('For OT hours, etc');
            $table->decimal('rate', 12, 2)->nullable()->comment('Per unit rate');
            $table->boolean('is_taxable')->default(true);
            $table->boolean('is_epf_applicable')->default(true);
            $table->boolean('is_socso_applicable')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['payroll_record_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
