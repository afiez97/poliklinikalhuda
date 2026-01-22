<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineCategory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Note: This seeder adds additional demo medicines.
     * PharmacySeeder should be run first to create categories.
     */
    public function run(): void
    {
        // Skip if medicines already exist (PharmacySeeder already ran)
        if (Medicine::count() > 10) {
            $this->command->info('MedicineSeeder: Skipped - Medicines already exist.');
            return;
        }

        // Get or create default category
        $defaultCategory = MedicineCategory::firstOrCreate(
            ['code' => 'MISC'],
            ['name' => 'Pelbagai', 'is_active' => true]
        );

        $medicines = [
            [
                'code' => Medicine::generateCode(),
                'name' => 'Paracetamol 500mg',
                'name_generic' => 'Paracetamol',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.30,
                'selling_price' => 0.50,
                'stock_quantity' => 500,
                'reorder_level' => 50,
                'max_stock_level' => 1000,
                'expiry_date' => Carbon::now()->addMonths(18),
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Amoxicillin 250mg',
                'name_generic' => 'Amoxicillin',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'capsule',
                'strength' => '250mg',
                'unit' => 'capsule',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.80,
                'selling_price' => 1.20,
                'stock_quantity' => 15, // Low stock
                'reorder_level' => 20,
                'max_stock_level' => 500,
                'expiry_date' => Carbon::now()->addMonths(12),
                'requires_prescription' => true,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Sirap Batuk 100ml',
                'name_generic' => 'Dextromethorphan',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'syrup',
                'strength' => '15mg/5ml',
                'unit' => 'bottle',
                'manufacturer' => 'CCM Pharma',
                'cost_price' => 5.00,
                'selling_price' => 8.50,
                'stock_quantity' => 80,
                'reorder_level' => 10,
                'max_stock_level' => 200,
                'expiry_date' => Carbon::now()->addDays(20), // Expiring soon
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Insulin 100IU/ml',
                'name_generic' => 'Human Insulin',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'injection',
                'strength' => '100IU/ml',
                'unit' => 'vial',
                'manufacturer' => 'Novo Nordisk',
                'cost_price' => 30.00,
                'selling_price' => 45.00,
                'stock_quantity' => 25,
                'reorder_level' => 5,
                'max_stock_level' => 100,
                'expiry_date' => Carbon::now()->addMonths(6),
                'storage_conditions' => 'refrigerate',
                'requires_prescription' => true,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Hydrocortisone Cream 1%',
                'name_generic' => 'Hydrocortisone',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'cream',
                'strength' => '1%',
                'unit' => 'tube',
                'manufacturer' => 'GSK',
                'cost_price' => 8.00,
                'selling_price' => 12.80,
                'stock_quantity' => 0, // Out of stock
                'reorder_level' => 10,
                'max_stock_level' => 100,
                'expiry_date' => Carbon::now()->addMonths(24),
                'requires_prescription' => true,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Artificial Tears 10ml',
                'name_generic' => 'Carboxymethylcellulose',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'drops',
                'strength' => '0.5%',
                'unit' => 'bottle',
                'manufacturer' => 'Alcon',
                'cost_price' => 10.00,
                'selling_price' => 15.60,
                'stock_quantity' => 35,
                'reorder_level' => 15,
                'max_stock_level' => 100,
                'expiry_date' => Carbon::now()->addMonths(8),
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Aspirin 100mg',
                'name_generic' => 'Acetylsalicylic Acid',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'tablet',
                'strength' => '100mg',
                'unit' => 'tablet',
                'manufacturer' => 'Bayer',
                'cost_price' => 0.50,
                'selling_price' => 0.80,
                'stock_quantity' => 200,
                'reorder_level' => 30,
                'max_stock_level' => 500,
                'expiry_date' => Carbon::now()->addDays(10), // Expiring very soon
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Tramadol 50mg',
                'name_generic' => 'Tramadol HCL',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'capsule',
                'strength' => '50mg',
                'unit' => 'capsule',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 1.50,
                'selling_price' => 2.50,
                'stock_quantity' => 100,
                'reorder_level' => 20,
                'max_stock_level' => 200,
                'expiry_date' => Carbon::now()->addMonths(12),
                'requires_prescription' => true,
                'is_controlled' => true,
                'poison_schedule' => 'B',
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Vitamin C 1000mg',
                'name_generic' => 'Ascorbic Acid',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'tablet',
                'strength' => '1000mg',
                'unit' => 'tablet',
                'manufacturer' => 'Blackmores',
                'cost_price' => 1.50,
                'selling_price' => 2.50,
                'stock_quantity' => 150,
                'reorder_level' => 25,
                'max_stock_level' => 300,
                'expiry_date' => Carbon::now()->addMonths(20),
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
            [
                'code' => Medicine::generateCode(),
                'name' => 'Ibuprofen 400mg',
                'name_generic' => 'Ibuprofen',
                'category_id' => $defaultCategory->id,
                'dosage_form' => 'tablet',
                'strength' => '400mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.80,
                'selling_price' => 1.50,
                'stock_quantity' => 75,
                'reorder_level' => 20,
                'max_stock_level' => 300,
                'expiry_date' => Carbon::now()->addMonths(14),
                'requires_prescription' => false,
                'is_controlled' => false,
                'is_active' => true,
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::firstOrCreate(
                ['name' => $medicine['name']],
                $medicine
            );
        }

        $this->command->info('MedicineSeeder: Created '.count($medicines).' additional medicines.');
    }
}
