<?php

namespace Database\Seeders;

use App\Models\Panel;
use App\Models\PanelContract;
use App\Models\PanelExclusion;
use App\Models\PanelPackage;
use Illuminate\Database\Seeder;

class PanelSeeder extends Seeder
{
    public function run(): void
    {
        // Create Panels
        $panels = [
            // Corporate Panels
            [
                'panel_code' => 'PAN-0001',
                'panel_name' => 'Petronas Carigali Sdn Bhd',
                'panel_type' => 'corporate',
                'contact_person' => 'Azman Ibrahim',
                'phone' => '03-20515000',
                'email' => 'hr.medical@petronas.com.my',
                'address' => 'Tower 1, PETRONAS Twin Towers',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '50088',
                'payment_terms_days' => 30,
                'sla_approval_days' => 7,
                'sla_payment_days' => 14,
                'status' => 'active',
            ],
            [
                'panel_code' => 'PAN-0002',
                'panel_name' => 'Tenaga Nasional Berhad',
                'panel_type' => 'corporate',
                'contact_person' => 'Siti Hajar',
                'phone' => '03-88882000',
                'email' => 'medical.panel@tnb.com.my',
                'address' => 'No. 129, Jalan Bangsar',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '59200',
                'payment_terms_days' => 45,
                'sla_approval_days' => 5,
                'sla_payment_days' => 21,
                'status' => 'active',
            ],
            // Insurance Panels
            [
                'panel_code' => 'PAN-0003',
                'panel_name' => 'AIA Bhd',
                'panel_type' => 'insurance',
                'contact_person' => 'Lee Mei Ling',
                'phone' => '03-21501800',
                'email' => 'claims@aia.com.my',
                'address' => 'Menara AIA, 99 Jalan Ampang',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '50450',
                'payment_terms_days' => 30,
                'sla_approval_days' => 3,
                'sla_payment_days' => 14,
                'status' => 'active',
            ],
            [
                'panel_code' => 'PAN-0004',
                'panel_name' => 'Prudential BSN Takaful Berhad',
                'panel_type' => 'insurance',
                'contact_person' => 'Ahmad Razali',
                'phone' => '03-27159800',
                'email' => 'medical.claims@prudentialbsn.com.my',
                'address' => 'Level 12, Menara Prudential',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '50250',
                'payment_terms_days' => 30,
                'sla_approval_days' => 5,
                'sla_payment_days' => 14,
                'status' => 'active',
            ],
            [
                'panel_code' => 'PAN-0005',
                'panel_name' => 'Great Eastern Life Assurance',
                'panel_type' => 'insurance',
                'contact_person' => 'Wong Siew Mei',
                'phone' => '03-42596888',
                'email' => 'claims@greateasternlife.com',
                'address' => 'Menara Great Eastern',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '50450',
                'payment_terms_days' => 30,
                'sla_approval_days' => 5,
                'sla_payment_days' => 14,
                'status' => 'active',
            ],
            // Government Panel
            [
                'panel_code' => 'PAN-0006',
                'panel_name' => 'PERKESO (SOCSO)',
                'panel_type' => 'government',
                'contact_person' => 'Encik Razak',
                'phone' => '03-40264000',
                'email' => 'claims@perkeso.gov.my',
                'address' => 'Menara PERKESO, 281 Jalan Ampang',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '50538',
                'payment_terms_days' => 60,
                'sla_approval_days' => 14,
                'sla_payment_days' => 30,
                'status' => 'active',
            ],
        ];

        foreach ($panels as $panelData) {
            $panel = Panel::create($panelData);

            // Create default packages
            $this->createPackages($panel);

            // Create contract
            $this->createContract($panel);

            // Create exclusions
            $this->createExclusions($panel);
        }

        $this->command->info('PanelSeeder: Created '.count($panels).' panels with packages, contracts, and exclusions.');
    }

    protected function createPackages(Panel $panel): void
    {
        $packages = [
            [
                'package_code' => 'STD',
                'package_name' => 'Standard',
                'description' => 'Pakej asas untuk pekerja',
                'annual_limit' => 3000.00,
                'per_visit_limit' => 200.00,
                'consultation_limit' => 50.00,
                'medication_limit' => 100.00,
                'procedure_limit' => 150.00,
                'lab_limit' => 100.00,
                'co_payment_percentage' => 20.00,
                'deductible_amount' => 0.00,
                'deductible_type' => 'per_visit',
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'package_code' => 'GOLD',
                'package_name' => 'Gold',
                'description' => 'Pakej untuk eksekutif',
                'annual_limit' => 5000.00,
                'per_visit_limit' => 300.00,
                'consultation_limit' => 80.00,
                'medication_limit' => 150.00,
                'procedure_limit' => 200.00,
                'lab_limit' => 150.00,
                'co_payment_percentage' => 10.00,
                'deductible_amount' => 0.00,
                'deductible_type' => 'per_visit',
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'package_code' => 'PLAT',
                'package_name' => 'Platinum',
                'description' => 'Pakej premium untuk pengurusan atasan',
                'annual_limit' => 10000.00,
                'per_visit_limit' => 500.00,
                'consultation_limit' => 100.00,
                'medication_limit' => 250.00,
                'procedure_limit' => 300.00,
                'lab_limit' => 200.00,
                'co_payment_percentage' => 0.00,
                'deductible_amount' => 0.00,
                'deductible_type' => 'per_visit',
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($packages as $packageData) {
            $packageData['panel_id'] = $panel->id;
            PanelPackage::create($packageData);
        }
    }

    protected function createContract(Panel $panel): void
    {
        PanelContract::create([
            'panel_id' => $panel->id,
            'contract_number' => 'CON-'.$panel->panel_code.'-'.now()->year,
            'effective_date' => now()->startOfYear(),
            'expiry_date' => now()->endOfYear(),
            'renewal_date' => now()->endOfYear()->subMonth(),
            'annual_cap' => 500000.00,
            'terms_conditions' => 'Terma dan syarat standard panel.',
            'status' => 'active',
            'created_by' => 1,
        ]);
    }

    protected function createExclusions(Panel $panel): void
    {
        $exclusions = [
            [
                'exclusion_type' => 'category',
                'exclusion_code' => 'COSMETIC',
                'exclusion_name' => 'Prosedur Kosmetik',
                'reason' => 'Tidak dilindungi di bawah polisi kesihatan',
            ],
            [
                'exclusion_type' => 'medication',
                'exclusion_code' => 'SUPP',
                'exclusion_name' => 'Suplemen & Vitamin',
                'reason' => 'Bukan ubat penting',
            ],
            [
                'exclusion_type' => 'procedure',
                'exclusion_code' => 'SCREEN',
                'exclusion_name' => 'Pemeriksaan Kesihatan (Medical Check-up)',
                'reason' => 'Perlu pre-authorization',
            ],
            [
                'exclusion_type' => 'diagnosis',
                'exclusion_code' => 'PREEXIST',
                'exclusion_name' => 'Penyakit Sedia Ada (Pre-existing)',
                'reason' => 'Tidak dilindungi dalam tempoh pertama',
            ],
        ];

        foreach ($exclusions as $exclusionData) {
            $exclusionData['panel_id'] = $panel->id;
            $exclusionData['is_active'] = true;
            PanelExclusion::create($exclusionData);
        }
    }
}
