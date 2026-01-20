<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default users
        DB::table('users')->insert([
            [
                'name' => 'Ahmad Sobri',
                'email' => 'ahmadsobriharis@gmail.com',
                'username' => 'sobrikiki89',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Afiez Adik',
                'email' => 'afiez@poliklinikalhuda.com',
                'username' => 'afiezadik97',
                'email_verified_at' => now(),
                'password' => Hash::make('afiez123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the users we added
        DB::table('users')->whereIn('username', [
            'sobrikiki89',
            'afiezadik97',
        ])->delete();
    }
};
