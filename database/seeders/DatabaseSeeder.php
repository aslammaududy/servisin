<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
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
        // User::factory(10)->create();

        $this->call([
            ServiceSeeder::class,
            DamageTypeSeeder::class,
        ]);

        // Seed default bank account settings
        SiteSetting::set('bank_name', 'BCA');
        SiteSetting::set('bank_account_number', '1234567890');
        SiteSetting::set('bank_account_name', 'PT Servisin Indonesia');

        //        User::factory()->create([
        //            'name' => 'Test User',
        //            'email' => 'test@example.com',
        //        ]);
    }
}
