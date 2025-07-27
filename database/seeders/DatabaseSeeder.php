<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default users are now created via migration file
        // See: database/migrations/2025_07_27_042852_add_default_users_to_users_table.php

        // Uncomment below if you need additional test users
        // User::factory(10)->create();

        /*
         * Default users already created via migration:
         * - admin@poliklinikalhuda.com (username: admin, password: admin123)
         * - dr.ahmad@poliklinikalhuda.com (username: dr.ahmad, password: doctor123)
         * - nurse.siti@poliklinikalhuda.com (username: nurse.siti, password: nurse123)
         * - afiez@poliklinikalhuda.com (username: afiezadik97, password: afiez123)
         */
    }
}
