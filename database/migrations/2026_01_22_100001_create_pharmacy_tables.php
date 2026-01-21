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
        // Medicine Categories
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('medicine_categories')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Medicines Catalog
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('barcode', 50)->nullable()->unique();
            $table->string('name');
            $table->string('name_generic')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('medicine_categories')->nullOnDelete();
            $table->string('dosage_form', 50)->nullable(); // tablet, capsule, syrup, injection, etc
            $table->string('strength', 100)->nullable(); // 500mg, 10ml, etc
            $table->string('unit', 30)->default('unit'); // tablet, bottle, vial, etc
            $table->string('manufacturer')->nullable();
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('max_stock_level')->default(1000);
            $table->date('expiry_date')->nullable();
            $table->string('storage_conditions')->nullable(); // room temp, refrigerate, etc
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_controlled')->default(false); // Akta Racun 1952
            $table->string('poison_schedule')->nullable(); // Group A, B, C, D
            $table->text('contraindications')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('dosage_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'name_generic']);
            $table->index('category_id');
            $table->index('is_active');
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('registration_no')->nullable(); // SSM number
            $table->string('tax_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->integer('payment_terms')->default(30); // days
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Medicine Stock Movements (for tracking stock in/out)
        Schema::create('medicine_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 50)->unique();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->enum('movement_type', ['in', 'out', 'adjustment', 'return', 'expired', 'damaged']);
            $table->integer('quantity');
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('batch_no', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('source_type')->nullable(); // purchase, dispensing, adjustment, etc
            $table->unsignedBigInteger('source_id')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['medicine_id', 'created_at']);
            $table->index('movement_type');
            $table->index('batch_no');
        });

        // Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 50)->unique();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('order_date');
            $table->date('expected_date')->nullable();
            $table->date('received_date')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'pending', 'approved', 'ordered', 'partial', 'received', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('order_date');
        });

        // Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 12, 2);
            $table->string('batch_no', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('purchase_order_id');
        });

        // Dispensing Records (ubat yang diberikan kepada pesakit)
        Schema::create('dispensing_records', function (Blueprint $table) {
            $table->id();
            $table->string('dispensing_no', 50)->unique();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('dispensed_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dispensed_at');
            $table->timestamp('verified_at')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'dispensed', 'partially_dispensed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'dispensed_at']);
            $table->index('status');
        });

        // Dispensing Items
        Schema::create('dispensing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispensing_record_id')->constrained('dispensing_records')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignId('prescription_item_id')->nullable()->constrained('prescription_items')->nullOnDelete();
            $table->integer('quantity_prescribed')->default(0);
            $table->integer('quantity_dispensed');
            $table->string('batch_no', 50)->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('dosage_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('dispensing_record_id');
        });

        // Poison Register (Daftar Racun - Akta Racun 1952)
        Schema::create('poison_registers', function (Blueprint $table) {
            $table->id();
            $table->string('register_no', 50)->unique();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('dispensing_item_id')->nullable()->constrained('dispensing_items')->nullOnDelete();
            $table->enum('transaction_type', ['received', 'dispensed', 'returned', 'destroyed']);
            $table->integer('quantity');
            $table->integer('balance_before');
            $table->integer('balance_after');
            $table->string('batch_no', 50)->nullable();
            $table->string('patient_name')->nullable();
            $table->string('patient_ic')->nullable();
            $table->text('patient_address')->nullable();
            $table->string('prescriber_name')->nullable();
            $table->string('prescriber_mmc')->nullable(); // MMC registration number
            $table->text('purpose')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('witnessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['medicine_id', 'created_at']);
            $table->index('transaction_type');
        });

        // Medicine Batches (for batch tracking and expiry management)
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained('medicines')->cascadeOnDelete();
            $table->string('batch_no', 50);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date');
            $table->integer('initial_quantity');
            $table->integer('current_quantity');
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->enum('status', ['active', 'low', 'expired', 'depleted'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['medicine_id', 'batch_no']);
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
        Schema::dropIfExists('poison_registers');
        Schema::dropIfExists('dispensing_items');
        Schema::dropIfExists('dispensing_records');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('medicine_stock_movements');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('medicine_categories');
    }
};
