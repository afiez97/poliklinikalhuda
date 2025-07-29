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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_code')->unique(); // Kod ubat unik
            $table->string('name'); // Nama ubat
            $table->text('description')->nullable(); // Keterangan ubat
            $table->enum('category', ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'drops', 'spray', 'patch']); // Kategori ubat
            $table->string('manufacturer')->nullable(); // Pengeluar
            $table->string('strength')->nullable(); // Kekuatan ubat (e.g., 500mg, 10ml)
            $table->decimal('unit_price', 10, 2); // Harga per unit
            $table->integer('stock_quantity'); // Kuantiti stok semasa
            $table->integer('minimum_stock')->default(10); // Stok minimum untuk alert
            $table->date('expiry_date')->nullable(); // Tarikh luput
            $table->string('batch_number')->nullable(); // Nombor batch
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active'); // Status ubat
            $table->timestamps();

            // Indexes untuk prestasi yang lebih baik
            $table->index(['status', 'category']);
            $table->index(['expiry_date']);
            $table->index(['stock_quantity', 'minimum_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
