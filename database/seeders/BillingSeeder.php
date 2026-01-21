<?php

namespace Database\Seeders;

use App\Models\BillingSetting;
use App\Models\Package;
use App\Models\PromoCode;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Billing Settings
        $this->createBillingSettings();

        // Create Packages
        $this->createPackages();

        // Create Promo Codes
        $this->createPromoCodes();

        $this->command->info('Billing seeder completed successfully!');
    }

    /**
     * Create billing settings.
     */
    protected function createBillingSettings(): void
    {
        $settings = [
            ['key' => 'sst_enabled', 'value' => '0', 'description' => 'Enable SST calculation'],
            ['key' => 'sst_rate', 'value' => '6', 'description' => 'SST rate percentage'],
            ['key' => 'rounding_enabled', 'value' => '1', 'description' => 'Enable rounding to nearest 5 sen'],
            ['key' => 'rounding_precision', 'value' => '5', 'description' => 'Rounding precision in sen'],
            ['key' => 'discount_approval_threshold', 'value' => '10', 'description' => 'Discount percentage requiring approval'],
            ['key' => 'max_discount_percentage', 'value' => '50', 'description' => 'Maximum discount percentage allowed'],
            ['key' => 'payment_terms_days', 'value' => '30', 'description' => 'Default payment terms in days'],
            ['key' => 'default_opening_balance', 'value' => '200', 'description' => 'Default cashier opening balance'],
            ['key' => 'receipt_prefix', 'value' => 'RCP', 'description' => 'Receipt number prefix'],
            ['key' => 'invoice_prefix', 'value' => 'INV', 'description' => 'Invoice number prefix'],
            ['key' => 'refund_approval_threshold', 'value' => '100', 'description' => 'Refund amount requiring approval'],
        ];

        foreach ($settings as $setting) {
            BillingSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Created billing settings');
    }

    /**
     * Create packages.
     */
    protected function createPackages(): void
    {
        $packages = [
            [
                'name' => 'Pakej Pemeriksaan Kesihatan Asas',
                'code' => 'PKG-HEALTH-BASIC',
                'description' => 'Pemeriksaan kesihatan asas termasuk konsultasi, ujian darah asas, dan tekanan darah.',
                'original_price' => 150.00,
                'price' => 120.00,
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'items' => [
                    ['item_type' => 'consultation', 'item_name' => 'Konsultasi Doktor', 'quantity' => 1, 'unit_price' => 50.00],
                    ['item_type' => 'lab', 'item_name' => 'Ujian Darah Penuh (FBC)', 'quantity' => 1, 'unit_price' => 40.00],
                    ['item_type' => 'lab', 'item_name' => 'Ujian Gula Darah', 'quantity' => 1, 'unit_price' => 25.00],
                    ['item_type' => 'procedure', 'item_name' => 'Pemeriksaan Tekanan Darah', 'quantity' => 1, 'unit_price' => 15.00],
                    ['item_type' => 'procedure', 'item_name' => 'Pengukuran BMI', 'quantity' => 1, 'unit_price' => 10.00],
                    ['item_type' => 'procedure', 'item_name' => 'Urinalisis', 'quantity' => 1, 'unit_price' => 10.00],
                ],
            ],
            [
                'name' => 'Pakej Pemeriksaan Kesihatan Komprehensif',
                'code' => 'PKG-HEALTH-COMP',
                'description' => 'Pemeriksaan kesihatan menyeluruh termasuk ujian darah lengkap, X-ray, dan ECG.',
                'original_price' => 450.00,
                'price' => 350.00,
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'items' => [
                    ['item_type' => 'consultation', 'item_name' => 'Konsultasi Doktor', 'quantity' => 1, 'unit_price' => 50.00],
                    ['item_type' => 'lab', 'item_name' => 'Ujian Darah Penuh (FBC)', 'quantity' => 1, 'unit_price' => 40.00],
                    ['item_type' => 'lab', 'item_name' => 'Profil Lipid', 'quantity' => 1, 'unit_price' => 60.00],
                    ['item_type' => 'lab', 'item_name' => 'Fungsi Hati (LFT)', 'quantity' => 1, 'unit_price' => 70.00],
                    ['item_type' => 'lab', 'item_name' => 'Fungsi Buah Pinggang (RFT)', 'quantity' => 1, 'unit_price' => 60.00],
                    ['item_type' => 'lab', 'item_name' => 'Ujian Gula Darah Puasa', 'quantity' => 1, 'unit_price' => 25.00],
                    ['item_type' => 'procedure', 'item_name' => 'X-Ray Dada', 'quantity' => 1, 'unit_price' => 80.00],
                    ['item_type' => 'procedure', 'item_name' => 'ECG', 'quantity' => 1, 'unit_price' => 50.00],
                    ['item_type' => 'procedure', 'item_name' => 'Urinalisis', 'quantity' => 1, 'unit_price' => 15.00],
                ],
            ],
            [
                'name' => 'Pakej Penjagaan Diabetes',
                'code' => 'PKG-DIABETES',
                'description' => 'Pakej pemantauan dan penjagaan khas untuk pesakit diabetes.',
                'original_price' => 200.00,
                'price' => 160.00,
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'items' => [
                    ['item_type' => 'consultation', 'item_name' => 'Konsultasi Doktor', 'quantity' => 1, 'unit_price' => 50.00],
                    ['item_type' => 'lab', 'item_name' => 'HbA1c', 'quantity' => 1, 'unit_price' => 70.00],
                    ['item_type' => 'lab', 'item_name' => 'Ujian Gula Darah Puasa', 'quantity' => 1, 'unit_price' => 25.00],
                    ['item_type' => 'lab', 'item_name' => 'Fungsi Buah Pinggang', 'quantity' => 1, 'unit_price' => 40.00],
                    ['item_type' => 'procedure', 'item_name' => 'Pemeriksaan Kaki Diabetik', 'quantity' => 1, 'unit_price' => 15.00],
                ],
            ],
            [
                'name' => 'Pakej Rawatan Luka',
                'code' => 'PKG-WOUND',
                'description' => 'Rawatan luka profesional dengan bahan berkualiti.',
                'original_price' => 80.00,
                'price' => 60.00,
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'items' => [
                    ['item_type' => 'procedure', 'item_name' => 'Rawatan Luka', 'quantity' => 1, 'unit_price' => 40.00],
                    ['item_type' => 'consumable', 'item_name' => 'Dressing Set', 'quantity' => 1, 'unit_price' => 25.00],
                    ['item_type' => 'consumable', 'item_name' => 'Antiseptik', 'quantity' => 1, 'unit_price' => 15.00],
                ],
            ],
            [
                'name' => 'Pakej Vaksinasi Influenza',
                'code' => 'PKG-FLU-VAX',
                'description' => 'Vaksin influenza dengan konsultasi pra-vaksinasi.',
                'original_price' => 120.00,
                'price' => 100.00,
                'is_active' => true,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'items' => [
                    ['item_type' => 'consultation', 'item_name' => 'Konsultasi Pra-Vaksinasi', 'quantity' => 1, 'unit_price' => 30.00],
                    ['item_type' => 'vaccination', 'item_name' => 'Vaksin Influenza', 'quantity' => 1, 'unit_price' => 80.00],
                    ['item_type' => 'procedure', 'item_name' => 'Suntikan', 'quantity' => 1, 'unit_price' => 10.00],
                ],
            ],
        ];

        foreach ($packages as $packageData) {
            $items = $packageData['items'];
            unset($packageData['items']);

            $package = Package::updateOrCreate(
                ['code' => $packageData['code']],
                $packageData
            );

            // Create package items
            $package->items()->delete();
            foreach ($items as $item) {
                $package->items()->create($item);
            }
        }

        $this->command->info('Created '.count($packages).' packages');
    }

    /**
     * Create promo codes.
     */
    protected function createPromoCodes(): void
    {
        $promoCodes = [
            [
                'code' => 'WELCOME10',
                'description' => 'Diskaun 10% untuk pesakit baru',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_purchase' => 50.00,
                'max_discount' => 50.00,
                'usage_limit' => 100,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'SENIOR15',
                'description' => 'Diskaun 15% untuk warga emas',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'min_purchase' => 100.00,
                'max_discount' => 100.00,
                'usage_limit' => null,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'STAFF20',
                'description' => 'Diskaun 20% untuk kakitangan',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_purchase' => 0,
                'max_discount' => 200.00,
                'usage_limit' => null,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'RM20OFF',
                'description' => 'Potongan RM20 untuk pembelian melebihi RM100',
                'discount_type' => 'fixed',
                'discount_value' => 20.00,
                'min_purchase' => 100.00,
                'max_discount' => null,
                'usage_limit' => 50,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'CHECKUP25',
                'description' => 'Diskaun 25% untuk pakej pemeriksaan kesihatan',
                'discount_type' => 'percentage',
                'discount_value' => 25,
                'min_purchase' => 200.00,
                'max_discount' => 150.00,
                'usage_limit' => 30,
                'usage_count' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(2),
                'is_active' => true,
            ],
        ];

        foreach ($promoCodes as $promo) {
            PromoCode::updateOrCreate(
                ['code' => $promo['code']],
                $promo
            );
        }

        $this->command->info('Created '.count($promoCodes).' promo codes');
    }
}
