<?php

namespace Database\Seeders;

use App\Models\KpiConfig;
use Illuminate\Database\Seeder;

class KpiConfigSeeder extends Seeder
{
    public function run(): void
    {
        $kpis = [
            // Financial KPIs
            [
                'code' => 'DAILY_REVENUE',
                'name' => 'Hasil Harian',
                'name_en' => 'Daily Revenue',
                'category' => 'financial',
                'metric_type' => 'sum',
                'unit' => 'RM',
                'target_value' => 5000,
                'warning_threshold' => 3500,
                'critical_threshold' => 3000,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 1,
            ],
            [
                'code' => 'MONTHLY_REVENUE',
                'name' => 'Hasil Bulanan',
                'name_en' => 'Monthly Revenue',
                'category' => 'financial',
                'metric_type' => 'sum',
                'unit' => 'RM',
                'target_value' => 150000,
                'warning_threshold' => 130000,
                'critical_threshold' => 120000,
                'comparison_operator' => '>=',
                'frequency' => 'monthly',
                'sort_order' => 2,
            ],
            [
                'code' => 'COLLECTION_RATE',
                'name' => 'Kadar Kutipan',
                'name_en' => 'Collection Rate',
                'category' => 'financial',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 90,
                'warning_threshold' => 85,
                'critical_threshold' => 80,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 3,
            ],
            [
                'code' => 'AVG_BILL_SIZE',
                'name' => 'Purata Saiz Bil',
                'name_en' => 'Average Bill Size',
                'category' => 'financial',
                'metric_type' => 'average',
                'unit' => 'RM',
                'target_value' => 80,
                'warning_threshold' => 70,
                'critical_threshold' => 60,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 4,
            ],

            // Clinical KPIs
            [
                'code' => 'DAILY_PATIENTS',
                'name' => 'Pesakit Harian',
                'name_en' => 'Daily Patients',
                'category' => 'clinical',
                'metric_type' => 'count',
                'unit' => null,
                'target_value' => 80,
                'warning_threshold' => 60,
                'critical_threshold' => 50,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 5,
            ],
            [
                'code' => 'AVG_CONSULT_TIME',
                'name' => 'Purata Masa Konsultasi',
                'name_en' => 'Average Consultation Time',
                'category' => 'clinical',
                'metric_type' => 'average',
                'unit' => 'minutes',
                'target_value' => 15,
                'warning_threshold' => 18,
                'critical_threshold' => 20,
                'comparison_operator' => '<=',
                'frequency' => 'daily',
                'sort_order' => 6,
            ],
            [
                'code' => 'PRESCRIPTION_RATE',
                'name' => 'Kadar Preskripsi',
                'name_en' => 'Prescription Rate',
                'category' => 'clinical',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 85,
                'warning_threshold' => null,
                'critical_threshold' => null,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 7,
            ],

            // Operational KPIs
            [
                'code' => 'AVG_WAIT_TIME',
                'name' => 'Purata Masa Menunggu',
                'name_en' => 'Average Wait Time',
                'category' => 'operational',
                'metric_type' => 'average',
                'unit' => 'minutes',
                'target_value' => 15,
                'warning_threshold' => 25,
                'critical_threshold' => 30,
                'comparison_operator' => '<=',
                'frequency' => 'realtime',
                'sort_order' => 8,
            ],
            [
                'code' => 'QUEUE_EFFICIENCY',
                'name' => 'Kecekapan Giliran',
                'name_en' => 'Queue Efficiency',
                'category' => 'operational',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 95,
                'warning_threshold' => 92,
                'critical_threshold' => 90,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 9,
            ],
            [
                'code' => 'NO_SHOW_RATE',
                'name' => 'Kadar Tidak Hadir',
                'name_en' => 'No-Show Rate',
                'category' => 'operational',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 10,
                'warning_threshold' => 12,
                'critical_threshold' => 15,
                'comparison_operator' => '<=',
                'frequency' => 'daily',
                'sort_order' => 10,
            ],

            // Customer KPIs
            [
                'code' => 'PATIENT_SATISFACTION',
                'name' => 'Kepuasan Pesakit',
                'name_en' => 'Patient Satisfaction',
                'category' => 'customer',
                'metric_type' => 'average',
                'unit' => null,
                'target_value' => 4.0,
                'warning_threshold' => 3.7,
                'critical_threshold' => 3.5,
                'comparison_operator' => '>=',
                'frequency' => 'weekly',
                'sort_order' => 11,
            ],
            [
                'code' => 'RETURN_VISIT_RATE',
                'name' => 'Kadar Lawatan Semula',
                'name_en' => 'Return Visit Rate',
                'category' => 'customer',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 35,
                'warning_threshold' => 25,
                'critical_threshold' => 20,
                'comparison_operator' => '>=',
                'frequency' => 'monthly',
                'sort_order' => 12,
            ],

            // Compliance KPIs
            [
                'code' => 'PANEL_SLA_COMPLIANCE',
                'name' => 'Pematuhan SLA Panel',
                'name_en' => 'Panel SLA Compliance',
                'category' => 'compliance',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 90,
                'warning_threshold' => 87,
                'critical_threshold' => 85,
                'comparison_operator' => '>=',
                'frequency' => 'weekly',
                'sort_order' => 13,
            ],
            [
                'code' => 'DATA_COMPLETENESS',
                'name' => 'Kelengkapan Data',
                'name_en' => 'Data Completeness',
                'category' => 'compliance',
                'metric_type' => 'percentage',
                'unit' => '%',
                'target_value' => 98,
                'warning_threshold' => 96,
                'critical_threshold' => 95,
                'comparison_operator' => '>=',
                'frequency' => 'daily',
                'sort_order' => 14,
            ],
        ];

        foreach ($kpis as $kpi) {
            KpiConfig::firstOrCreate(
                ['code' => $kpi['code']],
                $kpi
            );
        }

        $this->command->info('KpiConfigSeeder: Created ' . count($kpis) . ' KPI configurations.');
    }
}
