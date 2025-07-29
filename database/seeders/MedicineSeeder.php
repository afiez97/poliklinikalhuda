<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $medicines = [
            [
                'medicine_code' => 'MED000001',
                'name' => 'Paracetamol',
                'description' => 'Ubat demam dan sakit kepala',
                'category' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'strength' => '500mg',
                'unit_price' => 0.50,
                'stock_quantity' => 500,
                'minimum_stock' => 50,
                'expiry_date' => Carbon::now()->addMonths(18),
                'batch_number' => 'PAR2024001',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000002',
                'name' => 'Amoxicillin',
                'description' => 'Antibiotik untuk jangkitan bakteria',
                'category' => 'capsule',
                'manufacturer' => 'Duopharma',
                'strength' => '250mg',
                'unit_price' => 1.20,
                'stock_quantity' => 15, // Low stock
                'minimum_stock' => 20,
                'expiry_date' => Carbon::now()->addMonths(12),
                'batch_number' => 'AMX2024002',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000003',
                'name' => 'Cough Syrup',
                'description' => 'Sirap batuk untuk kanak-kanak dan dewasa',
                'category' => 'syrup',
                'manufacturer' => 'CCM Pharma',
                'strength' => '100ml',
                'unit_price' => 8.50,
                'stock_quantity' => 80,
                'minimum_stock' => 10,
                'expiry_date' => Carbon::now()->addDays(20), // Expiring soon
                'batch_number' => 'CSY2024003',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000004',
                'name' => 'Insulin Injection',
                'description' => 'Insulin untuk pesakit diabetes',
                'category' => 'injection',
                'manufacturer' => 'Novo Nordisk',
                'strength' => '100IU/ml',
                'unit_price' => 45.00,
                'stock_quantity' => 25,
                'minimum_stock' => 5,
                'expiry_date' => Carbon::now()->addMonths(6),
                'batch_number' => 'INS2024004',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000005',
                'name' => 'Hydrocortisone Cream',
                'description' => 'Krim untuk masalah kulit dan gatal-gatal',
                'category' => 'cream',
                'manufacturer' => 'GSK',
                'strength' => '1%',
                'unit_price' => 12.80,
                'stock_quantity' => 0, // Out of stock
                'minimum_stock' => 10,
                'expiry_date' => Carbon::now()->addMonths(24),
                'batch_number' => 'HYD2024005',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000006',
                'name' => 'Eye Drops',
                'description' => 'Titisan mata untuk mata kering',
                'category' => 'drops',
                'manufacturer' => 'Alcon',
                'strength' => '10ml',
                'unit_price' => 15.60,
                'stock_quantity' => 35,
                'minimum_stock' => 15,
                'expiry_date' => Carbon::now()->addMonths(8),
                'batch_number' => 'EYE2024006',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000007',
                'name' => 'Nasal Spray',
                'description' => 'Semburan hidung untuk selsema',
                'category' => 'spray',
                'manufacturer' => 'Reckitt Benckiser',
                'strength' => '20ml',
                'unit_price' => 18.90,
                'stock_quantity' => 3, // Very low stock
                'minimum_stock' => 8,
                'expiry_date' => Carbon::now()->addMonths(15),
                'batch_number' => 'NAS2024007',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000008',
                'name' => 'Nicotine Patch',
                'description' => 'Tampalan nikotin untuk berhenti merokok',
                'category' => 'patch',
                'manufacturer' => 'Johnson & Johnson',
                'strength' => '21mg/24h',
                'unit_price' => 25.50,
                'stock_quantity' => 40,
                'minimum_stock' => 10,
                'expiry_date' => Carbon::now()->addMonths(36),
                'batch_number' => 'NIC2024008',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000009',
                'name' => 'Aspirin',
                'description' => 'Ubat sakit kepala dan pengencer darah',
                'category' => 'tablet',
                'manufacturer' => 'Bayer',
                'strength' => '100mg',
                'unit_price' => 0.80,
                'stock_quantity' => 200,
                'minimum_stock' => 30,
                'expiry_date' => Carbon::now()->addDays(10), // Expiring very soon
                'batch_number' => 'ASP2024009',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000010',
                'name' => 'Expired Medicine',
                'description' => 'Ubat yang sudah luput untuk testing',
                'category' => 'tablet',
                'manufacturer' => 'Test Pharma',
                'strength' => '50mg',
                'unit_price' => 1.00,
                'stock_quantity' => 100,
                'minimum_stock' => 20,
                'expiry_date' => Carbon::now()->subDays(30), // Already expired
                'batch_number' => 'EXP2023010',
                'status' => 'expired',
            ],
            [
                'medicine_code' => 'MED000011',
                'name' => 'Vitamin C',
                'description' => 'Vitamin C untuk meningkatkan imuniti',
                'category' => 'tablet',
                'manufacturer' => 'Blackmores',
                'strength' => '1000mg',
                'unit_price' => 2.50,
                'stock_quantity' => 150,
                'minimum_stock' => 25,
                'expiry_date' => Carbon::now()->addMonths(20),
                'batch_number' => 'VTC2024011',
                'status' => 'active',
            ],
            [
                'medicine_code' => 'MED000012',
                'name' => 'Ibuprofen',
                'description' => 'Ubat anti-radang dan sakit',
                'category' => 'capsule',
                'manufacturer' => 'Pharmaniaga',
                'strength' => '400mg',
                'unit_price' => 1.50,
                'stock_quantity' => 75,
                'minimum_stock' => 20,
                'expiry_date' => Carbon::now()->addMonths(14),
                'batch_number' => 'IBU2024012',
                'status' => 'active',
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
