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
        // Packages (Service bundles)
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('name_en', 100)->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable()->comment('Sum of individual items');
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });

        // Package Items
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('item_type', 50)->comment('consultation, medication, procedure, lab_test, other');
            $table->string('item_name', 255);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
        });

        // Promo Codes
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('description', 255)->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2);
            $table->decimal('min_purchase', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable()->comment('For percentage type');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('patient_visit_id')->nullable()->constrained('patient_visits')->nullOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->enum('discount_type', ['percentage', 'fixed', 'promo_code', 'senior', 'staff', 'none'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->decimal('taxable_amount', 10, 2)->default(0);
            $table->decimal('sst_rate', 5, 2)->default(6.00);
            $table->decimal('sst_amount', 10, 2)->default(0);
            $table->decimal('rounding_adjustment', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('balance_owed', 10, 2)->default(0);
            $table->enum('status', [
                'draft',
                'pending_payment',
                'partially_paid',
                'fully_paid',
                'overdue',
                'voided',
                'refunded',
            ])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();
            $table->timestamps();

            $table->index(['invoice_date', 'status']);
            $table->index(['patient_id', 'status']);
        });

        // Invoice Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['consultation', 'medication', 'procedure', 'lab_test', 'package', 'other'])->default('other');
            $table->string('item_code', 50)->nullable();
            $table->string('item_name', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('line_total', 10, 2);
            $table->boolean('is_taxable')->default(false);
            $table->morphs('billable'); // For linking to encounter, prescription, etc.
            $table->timestamps();
        });

        // Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number', 50)->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', [
                'cash',
                'card',
                'qr_pay',
                'ewallet_tng',
                'ewallet_grabpay',
                'ewallet_boost',
                'bank_transfer',
                'panel',
                'deposit',
            ]);
            $table->string('reference_number', 100)->nullable()->comment('Card approval code, QR ref, etc.');
            $table->string('card_type', 20)->nullable()->comment('visa, mastercard, etc.');
            $table->string('card_last4', 4)->nullable();
            $table->string('ewallet_provider', 50)->nullable();
            $table->unsignedBigInteger('panel_id')->nullable()->comment('Reference to panels table when created');
            $table->string('panel_name')->nullable()->comment('Panel/Corporate name');
            $table->decimal('change_amount', 10, 2)->default(0)->comment('For cash payments');
            $table->timestamp('payment_date');
            $table->enum('status', ['pending', 'completed', 'failed', 'voided', 'refunded'])->default('completed');
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();
            $table->string('void_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['payment_date', 'status']);
            $table->index(['payment_method', 'payment_date']);
        });

        // Receipts
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 50)->unique();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->timestamp('receipt_date');
            $table->boolean('is_printed')->default(false);
            $table->boolean('is_emailed')->default(false);
            $table->string('email_sent_to')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Deposits
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('deposit_number', 50)->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('used_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->string('purpose', 255)->nullable();
            $table->enum('payment_method', ['cash', 'card', 'qr_pay', 'ewallet', 'bank_transfer']);
            $table->string('reference_number', 100)->nullable();
            $table->enum('status', ['active', 'partially_used', 'fully_used', 'refunded'])->default('active');
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['patient_id', 'status']);
        });

        // Refunds
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_number', 50)->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('refund_method', ['cash', 'card_reversal', 'bank_transfer', 'ewallet']);
            $table->text('reason');
            $table->enum('status', ['pending_approval', 'approved', 'rejected', 'processed'])->default('pending_approval');
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->string('reference_number', 100)->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        // Credit Notes
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->string('credit_note_number', 50)->unique();
            $table->foreignId('refund_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->timestamp('issue_date');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Discount Approvals
        Schema::create('discount_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->enum('discount_type', ['percentage', 'fixed', 'promo_code', 'senior', 'staff', 'other']);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('original_total', 10, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Outstanding Reminders
        Schema::create('outstanding_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->enum('channel', ['sms', 'whatsapp', 'email']);
            $table->string('sent_to', 100);
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->string('error_message')->nullable();
            $table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Cashier Closing
        Schema::create('cashier_closings', function (Blueprint $table) {
            $table->id();
            $table->string('closing_number', 50)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('closing_date');
            $table->time('shift_start');
            $table->time('shift_end');

            // Expected amounts (from system)
            $table->decimal('expected_cash', 10, 2)->default(0);
            $table->decimal('expected_card', 10, 2)->default(0);
            $table->decimal('expected_qr_pay', 10, 2)->default(0);
            $table->decimal('expected_ewallet', 10, 2)->default(0);
            $table->decimal('expected_bank_transfer', 10, 2)->default(0);
            $table->decimal('expected_total', 10, 2)->default(0);

            // Actual amounts (counted by cashier)
            $table->decimal('actual_cash', 10, 2)->default(0);
            $table->decimal('actual_card', 10, 2)->default(0);
            $table->decimal('actual_qr_pay', 10, 2)->default(0);
            $table->decimal('actual_ewallet', 10, 2)->default(0);
            $table->decimal('actual_bank_transfer', 10, 2)->default(0);
            $table->decimal('actual_total', 10, 2)->default(0);

            // Variance
            $table->decimal('variance', 10, 2)->default(0);
            $table->text('variance_explanation')->nullable();

            // Counts
            $table->unsignedInteger('total_invoices')->default(0);
            $table->unsignedInteger('total_payments')->default(0);
            $table->unsignedInteger('total_refunds')->default(0);

            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'closing_date'], 'cashier_closing_unique');
        });

        // Billing Settings
        Schema::create('billing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 50)->unique();
            $table->text('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default billing settings
        DB::table('billing_settings')->insert([
            ['key' => 'sst_rate', 'value' => '6.00', 'description' => 'SST rate (%)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sst_enabled', 'value' => 'true', 'description' => 'Enable SST calculation', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'rounding_enabled', 'value' => 'true', 'description' => 'Enable rounding to 5 sen', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'discount_approval_threshold', 'value' => '50.00', 'description' => 'Discount amount requiring approval (RM)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'refund_approval_threshold', 'value' => '100.00', 'description' => 'Refund amount requiring approval (RM)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'senior_discount_rate', 'value' => '10.00', 'description' => 'Senior citizen discount (%)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'senior_age_threshold', 'value' => '60', 'description' => 'Age to qualify as senior', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'staff_discount_rate', 'value' => '20.00', 'description' => 'Staff discount (%)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'description' => 'Invoice number prefix', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'receipt_prefix', 'value' => 'RCP', 'description' => 'Receipt number prefix', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'payment_terms_days', 'value' => '7', 'description' => 'Default payment terms (days)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'outstanding_reminder_days', 'value' => '7,14,30', 'description' => 'Days after due date to send reminders', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_settings');
        Schema::dropIfExists('cashier_closings');
        Schema::dropIfExists('outstanding_reminders');
        Schema::dropIfExists('discount_approvals');
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('packages');
    }
};
