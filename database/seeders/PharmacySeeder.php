<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineCategory;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class PharmacySeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['code' => 'CAT0001', 'name' => 'Analgesik & Antipiretik', 'name_en' => 'Analgesics & Antipyretics'],
            ['code' => 'CAT0002', 'name' => 'Antibiotik', 'name_en' => 'Antibiotics'],
            ['code' => 'CAT0003', 'name' => 'Antihistamin', 'name_en' => 'Antihistamines'],
            ['code' => 'CAT0004', 'name' => 'Antihipertensi', 'name_en' => 'Antihypertensives'],
            ['code' => 'CAT0005', 'name' => 'Antidiabetik', 'name_en' => 'Antidiabetics'],
            ['code' => 'CAT0006', 'name' => 'Gastrointestinal', 'name_en' => 'Gastrointestinal'],
            ['code' => 'CAT0007', 'name' => 'Vitamin & Suplemen', 'name_en' => 'Vitamins & Supplements'],
            ['code' => 'CAT0008', 'name' => 'Topikal', 'name_en' => 'Topical'],
            ['code' => 'CAT0009', 'name' => 'Pernafasan', 'name_en' => 'Respiratory'],
            ['code' => 'CAT0010', 'name' => 'Kardiovaskular', 'name_en' => 'Cardiovascular'],
        ];

        foreach ($categories as $category) {
            MedicineCategory::create(array_merge($category, ['is_active' => true]));
        }

        // Create Suppliers
        $suppliers = [
            [
                'code' => 'SUP0001',
                'name' => 'Duopharma Biotech Berhad',
                'contact_person' => 'Ahmad Razak',
                'phone' => '03-61424348',
                'email' => 'sales@duopharma.com.my',
                'address' => 'Lot 2A, Jalan 13/2, Seksyen 13',
                'city' => 'Petaling Jaya',
                'state' => 'Selangor',
                'postcode' => '46200',
                'payment_terms' => 30,
                'is_active' => true,
            ],
            [
                'code' => 'SUP0002',
                'name' => 'Pharmaniaga Berhad',
                'contact_person' => 'Siti Aminah',
                'phone' => '03-33424999',
                'email' => 'order@pharmaniaga.com',
                'address' => 'No. 7, Lorong Keluli 1B, Kawasan Perindustrian Bukit Raja Selatan',
                'city' => 'Shah Alam',
                'state' => 'Selangor',
                'postcode' => '40000',
                'payment_terms' => 45,
                'is_active' => true,
            ],
            [
                'code' => 'SUP0003',
                'name' => 'CCM Pharmaceuticals Sdn Bhd',
                'contact_person' => 'Lee Wei Ming',
                'phone' => '03-79570888',
                'email' => 'sales@ccm.com.my',
                'address' => 'Level 19, Menara CCM, Jalan Kerinchi',
                'city' => 'Kuala Lumpur',
                'state' => 'WP Kuala Lumpur',
                'postcode' => '59200',
                'payment_terms' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create Medicines
        $medicines = [
            // Analgesik & Antipiretik
            [
                'code' => 'MED00001',
                'name' => 'Paracetamol',
                'name_generic' => 'Acetaminophen',
                'category_id' => 1,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.05,
                'selling_price' => 0.15,
                'stock_quantity' => 5000,
                'reorder_level' => 500,
                'dosage_instructions' => '1-2 tablet setiap 4-6 jam jika perlu. Maksimum 8 tablet sehari.',
            ],
            [
                'code' => 'MED00002',
                'name' => 'Ibuprofen',
                'name_generic' => 'Ibuprofen',
                'category_id' => 1,
                'dosage_form' => 'tablet',
                'strength' => '400mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.08,
                'selling_price' => 0.25,
                'stock_quantity' => 3000,
                'reorder_level' => 300,
                'dosage_instructions' => '1 tablet 3 kali sehari selepas makan.',
            ],
            [
                'code' => 'MED00003',
                'name' => 'Mefenamic Acid',
                'name_generic' => 'Mefenamic Acid',
                'category_id' => 1,
                'dosage_form' => 'capsule',
                'strength' => '250mg',
                'unit' => 'kapsul',
                'manufacturer' => 'CCM',
                'cost_price' => 0.12,
                'selling_price' => 0.35,
                'stock_quantity' => 2000,
                'reorder_level' => 200,
                'dosage_instructions' => '1-2 kapsul 3 kali sehari selepas makan.',
            ],

            // Antibiotik
            [
                'code' => 'MED00004',
                'name' => 'Amoxicillin',
                'name_generic' => 'Amoxicillin Trihydrate',
                'category_id' => 2,
                'dosage_form' => 'capsule',
                'strength' => '500mg',
                'unit' => 'kapsul',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.20,
                'selling_price' => 0.50,
                'stock_quantity' => 2000,
                'reorder_level' => 200,
                'requires_prescription' => true,
                'dosage_instructions' => '1 kapsul 3 kali sehari selama 5-7 hari.',
            ],
            [
                'code' => 'MED00005',
                'name' => 'Azithromycin',
                'name_generic' => 'Azithromycin Dihydrate',
                'category_id' => 2,
                'dosage_form' => 'tablet',
                'strength' => '250mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.80,
                'selling_price' => 2.00,
                'stock_quantity' => 1000,
                'reorder_level' => 100,
                'requires_prescription' => true,
                'dosage_instructions' => '2 tablet hari pertama, kemudian 1 tablet sehari selama 4 hari.',
            ],
            [
                'code' => 'MED00006',
                'name' => 'Cefuroxime',
                'name_generic' => 'Cefuroxime Axetil',
                'category_id' => 2,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'tablet',
                'manufacturer' => 'CCM',
                'cost_price' => 1.50,
                'selling_price' => 3.50,
                'stock_quantity' => 500,
                'reorder_level' => 50,
                'requires_prescription' => true,
                'dosage_instructions' => '1 tablet 2 kali sehari selepas makan.',
            ],

            // Antihistamin
            [
                'code' => 'MED00007',
                'name' => 'Cetirizine',
                'name_generic' => 'Cetirizine Dihydrochloride',
                'category_id' => 3,
                'dosage_form' => 'tablet',
                'strength' => '10mg',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.10,
                'selling_price' => 0.30,
                'stock_quantity' => 3000,
                'reorder_level' => 300,
                'dosage_instructions' => '1 tablet sekali sehari.',
            ],
            [
                'code' => 'MED00008',
                'name' => 'Loratadine',
                'name_generic' => 'Loratadine',
                'category_id' => 3,
                'dosage_form' => 'tablet',
                'strength' => '10mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.15,
                'selling_price' => 0.40,
                'stock_quantity' => 2500,
                'reorder_level' => 250,
                'dosage_instructions' => '1 tablet sekali sehari.',
            ],
            [
                'code' => 'MED00009',
                'name' => 'Chlorpheniramine Maleate',
                'name_generic' => 'Chlorpheniramine Maleate',
                'category_id' => 3,
                'dosage_form' => 'tablet',
                'strength' => '4mg',
                'unit' => 'tablet',
                'manufacturer' => 'CCM',
                'cost_price' => 0.03,
                'selling_price' => 0.10,
                'stock_quantity' => 5000,
                'reorder_level' => 500,
                'dosage_instructions' => '1 tablet 3-4 kali sehari. Boleh menyebabkan mengantuk.',
            ],

            // Antihipertensi
            [
                'code' => 'MED00010',
                'name' => 'Amlodipine',
                'name_generic' => 'Amlodipine Besylate',
                'category_id' => 4,
                'dosage_form' => 'tablet',
                'strength' => '5mg',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.15,
                'selling_price' => 0.45,
                'stock_quantity' => 2000,
                'reorder_level' => 200,
                'requires_prescription' => true,
                'dosage_instructions' => '1 tablet sekali sehari.',
            ],
            [
                'code' => 'MED00011',
                'name' => 'Losartan',
                'name_generic' => 'Losartan Potassium',
                'category_id' => 4,
                'dosage_form' => 'tablet',
                'strength' => '50mg',
                'unit' => 'tablet',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.25,
                'selling_price' => 0.60,
                'stock_quantity' => 1500,
                'reorder_level' => 150,
                'requires_prescription' => true,
                'dosage_instructions' => '1 tablet sekali sehari.',
            ],

            // Antidiabetik
            [
                'code' => 'MED00012',
                'name' => 'Metformin',
                'name_generic' => 'Metformin Hydrochloride',
                'category_id' => 5,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.08,
                'selling_price' => 0.25,
                'stock_quantity' => 3000,
                'reorder_level' => 300,
                'requires_prescription' => true,
                'dosage_instructions' => '1 tablet 2-3 kali sehari bersama makanan.',
            ],
            [
                'code' => 'MED00013',
                'name' => 'Gliclazide',
                'name_generic' => 'Gliclazide',
                'category_id' => 5,
                'dosage_form' => 'tablet',
                'strength' => '80mg',
                'unit' => 'tablet',
                'manufacturer' => 'CCM',
                'cost_price' => 0.20,
                'selling_price' => 0.50,
                'stock_quantity' => 1500,
                'reorder_level' => 150,
                'requires_prescription' => true,
                'dosage_instructions' => '1 tablet 2 kali sehari sebelum makan.',
            ],

            // Gastrointestinal
            [
                'code' => 'MED00014',
                'name' => 'Omeprazole',
                'name_generic' => 'Omeprazole',
                'category_id' => 6,
                'dosage_form' => 'capsule',
                'strength' => '20mg',
                'unit' => 'kapsul',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 0.25,
                'selling_price' => 0.60,
                'stock_quantity' => 2000,
                'reorder_level' => 200,
                'dosage_instructions' => '1 kapsul sekali sehari sebelum makan pagi.',
            ],
            [
                'code' => 'MED00015',
                'name' => 'Domperidone',
                'name_generic' => 'Domperidone Maleate',
                'category_id' => 6,
                'dosage_form' => 'tablet',
                'strength' => '10mg',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.12,
                'selling_price' => 0.35,
                'stock_quantity' => 2500,
                'reorder_level' => 250,
                'dosage_instructions' => '1 tablet 3 kali sehari sebelum makan.',
            ],

            // Vitamin & Suplemen
            [
                'code' => 'MED00016',
                'name' => 'Vitamin C',
                'name_generic' => 'Ascorbic Acid',
                'category_id' => 7,
                'dosage_form' => 'tablet',
                'strength' => '500mg',
                'unit' => 'tablet',
                'manufacturer' => 'CCM',
                'cost_price' => 0.05,
                'selling_price' => 0.15,
                'stock_quantity' => 5000,
                'reorder_level' => 500,
                'dosage_instructions' => '1-2 tablet sekali sehari.',
            ],
            [
                'code' => 'MED00017',
                'name' => 'Vitamin B Complex',
                'name_generic' => 'Vitamin B Complex',
                'category_id' => 7,
                'dosage_form' => 'tablet',
                'strength' => '',
                'unit' => 'tablet',
                'manufacturer' => 'Duopharma',
                'cost_price' => 0.08,
                'selling_price' => 0.20,
                'stock_quantity' => 4000,
                'reorder_level' => 400,
                'dosage_instructions' => '1 tablet sekali sehari.',
            ],

            // Topikal
            [
                'code' => 'MED00018',
                'name' => 'Hydrocortisone Cream',
                'name_generic' => 'Hydrocortisone',
                'category_id' => 8,
                'dosage_form' => 'cream',
                'strength' => '1%',
                'unit' => 'tiub',
                'manufacturer' => 'Pharmaniaga',
                'cost_price' => 2.50,
                'selling_price' => 6.00,
                'stock_quantity' => 200,
                'reorder_level' => 20,
                'dosage_instructions' => 'Sapukan nipis pada kawasan yang terlibat 2-3 kali sehari.',
            ],
            [
                'code' => 'MED00019',
                'name' => 'Chlorhexidine Solution',
                'name_generic' => 'Chlorhexidine Gluconate',
                'category_id' => 8,
                'dosage_form' => 'solution',
                'strength' => '0.5%',
                'unit' => 'botol',
                'manufacturer' => 'CCM',
                'cost_price' => 3.00,
                'selling_price' => 7.50,
                'stock_quantity' => 150,
                'reorder_level' => 15,
                'dosage_instructions' => 'Untuk kegunaan luar sahaja. Cuci luka dengan larutan ini.',
            ],

            // Pernafasan
            [
                'code' => 'MED00020',
                'name' => 'Salbutamol Inhaler',
                'name_generic' => 'Salbutamol Sulfate',
                'category_id' => 9,
                'dosage_form' => 'inhaler',
                'strength' => '100mcg/puff',
                'unit' => 'inhaler',
                'manufacturer' => 'Duopharma',
                'cost_price' => 8.00,
                'selling_price' => 18.00,
                'stock_quantity' => 100,
                'reorder_level' => 10,
                'requires_prescription' => true,
                'dosage_instructions' => '1-2 sedutan bila perlu. Maksimum 8 sedutan sehari.',
            ],
        ];

        foreach ($medicines as $medicineData) {
            $medicine = Medicine::create(array_merge($medicineData, [
                'is_active' => true,
                'expiry_date' => now()->addYears(2),
            ]));

            // Create initial batch for each medicine
            MedicineBatch::create([
                'medicine_id' => $medicine->id,
                'batch_no' => 'BATCH'.str_pad($medicine->id, 5, '0', STR_PAD_LEFT),
                'expiry_date' => now()->addYears(2),
                'initial_quantity' => $medicine->stock_quantity,
                'current_quantity' => $medicine->stock_quantity,
                'cost_price' => $medicine->cost_price,
                'supplier_id' => rand(1, 3),
                'status' => 'active',
            ]);
        }

        $this->command->info('PharmacySeeder: Created '.count($categories).' categories, '.count($suppliers).' suppliers, and '.count($medicines).' medicines.');
    }
}
