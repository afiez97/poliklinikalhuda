<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Seed EMR reference data
        $this->call([
            IcdCodeSeeder::class,
            ClinicalTemplateSeeder::class,
        ]);

        // Seed Queue Management data
        $this->call([
            QueueSeeder::class,
        ]);

        // Seed Billing data
        $this->call([
            BillingSeeder::class,
        ]);

        // Seed demo data (optional - for testing/demonstration)
        if (app()->environment('local', 'staging')) {
            $this->call([
                DemoEncounterSeeder::class,
            ]);
        }
    }
}
