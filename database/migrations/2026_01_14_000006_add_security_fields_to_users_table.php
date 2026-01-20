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
        Schema::table('users', function (Blueprint $table) {
            // Security fields
            $table->string('phone', 20)->nullable()->after('email');
            $table->enum('status', ['active', 'inactive', 'suspended', 'pending'])->default('pending')->after('phone');
            $table->boolean('mfa_enabled')->default(false)->after('status');
            $table->boolean('mfa_required')->default(false)->after('mfa_enabled');

            // Password policy
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->json('password_history')->nullable()->after('password_changed_at');
            $table->boolean('must_change_password')->default(false)->after('password_history');

            // Login tracking
            $table->timestamp('last_login_at')->nullable()->after('must_change_password');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->timestamp('last_activity_at')->nullable()->after('last_login_ip');
            $table->integer('failed_login_attempts')->default(0)->after('last_activity_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');

            // Metadata
            $table->foreignId('created_by')->nullable()->after('locked_until');
            $table->foreignId('updated_by')->nullable()->after('created_by');
            $table->softDeletes()->after('updated_at');

            // Indexes
            $table->index('status');
            $table->index('last_login_at');
            $table->index('last_activity_at');
        });

        // Add foreign key constraints separately
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn([
                'phone',
                'status',
                'mfa_enabled',
                'mfa_required',
                'password_changed_at',
                'password_history',
                'must_change_password',
                'last_login_at',
                'last_login_ip',
                'last_activity_at',
                'failed_login_attempts',
                'locked_until',
                'created_by',
                'updated_by',
                'deleted_at',
            ]);
        });
    }
};
