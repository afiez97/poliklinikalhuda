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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->enum('type', ['national', 'state', 'company', 'replacement'])->default('national');
            $table->string('state', 50)->nullable()->comment('For state-specific holidays');
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();

            $table->unique(['holiday_date', 'state']);
            $table->index('holiday_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
