<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('services')->insert([
            [
                'name' => 'Servis AC',
                'description' => 'Layanan perbaikan dan perawatan AC',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Servis TV',
                'description' => 'Layanan perbaikan televisi',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Servis Mesin Cuci',
                'description' => 'Layanan perbaikan mesin cuci',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Servis Kulkas',
                'description' => 'Layanan perbaikan kulkas dan freezer',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
