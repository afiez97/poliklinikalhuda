<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // KPI Configurations
        if (!Schema::hasTable('kpi_configs')) {
            Schema::create('kpi_configs', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->string('category'); // financial, clinical, operational, customer, compliance
                $table->string('metric_type'); // count, sum, average, percentage, ratio
                $table->text('formula')->nullable(); // SQL or calculation formula
                $table->string('unit')->nullable(); // RM, %, count, minutes
                $table->decimal('target_value', 12, 2)->nullable();
                $table->decimal('warning_threshold', 12, 2)->nullable();
                $table->decimal('critical_threshold', 12, 2)->nullable();
                $table->enum('comparison_operator', ['>=', '<=', '='])->default('>=');
                $table->string('frequency')->default('daily'); // realtime, hourly, daily, weekly, monthly
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->json('config')->nullable(); // additional configuration
                $table->timestamps();
            });
        }

        // Report Templates
        if (!Schema::hasTable('report_templates')) {
            Schema::create('report_templates', function (Blueprint $table) {
                $table->id();
                $table->string('code', 50)->unique();
                $table->string('name');
                $table->string('category'); // daily, weekly, monthly, quarterly, annual, custom
                $table->text('description')->nullable();
                $table->json('config'); // columns, filters, grouping, charts
                $table->json('default_filters')->nullable();
                $table->boolean('is_system')->default(false); // system templates cannot be deleted
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Scheduled Reports
        if (!Schema::hasTable('report_schedules')) {
            Schema::create('report_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_template_id')->constrained('report_templates')->cascadeOnDelete();
                $table->string('name');
                $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly']);
                $table->string('day_of_week')->nullable(); // for weekly: monday, tuesday, etc
                $table->integer('day_of_month')->nullable(); // for monthly: 1-28
                $table->time('time_of_day')->default('08:00:00');
                $table->json('recipients'); // email addresses
                $table->json('formats')->default('["pdf"]'); // pdf, excel, csv
                $table->json('filters')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_run_at')->nullable();
                $table->timestamp('next_run_at')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Report Execution History
        if (!Schema::hasTable('report_executions')) {
            Schema::create('report_executions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_schedule_id')->nullable()->constrained('report_schedules')->nullOnDelete();
                $table->foreignId('report_template_id')->nullable()->constrained('report_templates')->nullOnDelete();
                $table->enum('status', ['pending', 'running', 'completed', 'failed']);
                $table->json('parameters')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_format')->nullable();
                $table->integer('file_size')->nullable();
                $table->integer('record_count')->nullable();
                $table->integer('execution_time_ms')->nullable();
                $table->text('error_message')->nullable();
                $table->foreignId('executed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // KPI Snapshots (for historical tracking)
        if (!Schema::hasTable('kpi_snapshots')) {
            Schema::create('kpi_snapshots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('kpi_config_id')->constrained('kpi_configs')->cascadeOnDelete();
                $table->date('snapshot_date');
                $table->decimal('value', 12, 2);
                $table->decimal('target', 12, 2)->nullable();
                $table->enum('status', ['good', 'warning', 'critical'])->nullable();
                $table->json('breakdown')->nullable(); // detailed breakdown if applicable
                $table->timestamps();

                $table->unique(['kpi_config_id', 'snapshot_date']);
                $table->index('snapshot_date');
            });
        }

        // Dashboard Widgets Configuration
        if (!Schema::hasTable('dashboard_widgets')) {
            Schema::create('dashboard_widgets', function (Blueprint $table) {
                $table->id();
                $table->string('dashboard_type'); // executive, operational, clinical, pharmacy, billing
                $table->string('widget_type'); // metric, chart, table, gauge
                $table->string('title');
                $table->string('data_source'); // kpi_code or custom query identifier
                $table->json('config'); // chart type, colors, size, position
                $table->integer('position_x')->default(0);
                $table->integer('position_y')->default(0);
                $table->integer('width')->default(3); // grid units (1-12)
                $table->integer('height')->default(2);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // Saved Report Shares (for link sharing)
        if (!Schema::hasTable('report_shares')) {
            Schema::create('report_shares', function (Blueprint $table) {
                $table->id();
                $table->string('token', 64)->unique();
                $table->foreignId('report_execution_id')->constrained('report_executions')->cascadeOnDelete();
                $table->string('password')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->integer('view_count')->default(0);
                $table->integer('max_views')->nullable();
                $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();

                $table->index('token');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('report_shares');
        Schema::dropIfExists('dashboard_widgets');
        Schema::dropIfExists('kpi_snapshots');
        Schema::dropIfExists('report_executions');
        Schema::dropIfExists('report_schedules');
        Schema::dropIfExists('report_templates');
        Schema::dropIfExists('kpi_configs');
    }
};
