<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DamageTypeSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $services = DB::table('services')
            ->pluck('id', 'name');

        $data = [
            // 🔧 SERVIS AC
            'Servis AC' => [
                ['name' => 'AC tidak dingin', 'price' => 150000],
                ['name' => 'AC bocor / menetes', 'price' => 175000],
                ['name' => 'AC mati total', 'price' => 200000],
                ['name' => 'AC berisik', 'price' => 125000],
                ['name' => 'AC bau tidak sedap', 'price' => 100000],
                ['name' => 'Freon habis / bocor', 'price' => 250000],
            ],

            // 📺 SERVIS TV
            'Servis TV' => [
                ['name' => 'TV tidak menyala', 'price' => 180000],
                ['name' => 'Layar gelap / blank', 'price' => 220000],
                ['name' => 'Layar bergaris', 'price' => 250000],
                ['name' => 'Suara ada, gambar tidak ada', 'price' => 200000],
                ['name' => 'Gambar ada, suara tidak ada', 'price' => 170000],
            ],

            // 🧺 MESIN CUCI
            'Servis Mesin Cuci' => [
                ['name' => 'Mesin tidak menyala', 'price' => 180000],
                ['name' => 'Tidak bisa berputar', 'price' => 200000],
                ['name' => 'Air tidak masuk', 'price' => 150000],
                ['name' => 'Air tidak keluar', 'price' => 160000],
                ['name' => 'Mesin berisik / getar keras', 'price' => 190000],
            ],

            // ❄️ KULKAS
            'Servis Kulkas' => [
                ['name' => 'Kulkas tidak dingin', 'price' => 220000],
                ['name' => 'Bocor air', 'price' => 160000],
                ['name' => 'Bunga es berlebihan', 'price' => 180000],
                ['name' => 'Kompresor tidak jalan', 'price' => 350000],
                ['name' => 'Bunyi tidak normal', 'price' => 200000],
            ],
        ];

        foreach ($data as $serviceName => $damages) {
            $serviceId = $services[$serviceName] ?? null;

            if (!$serviceId) {
                continue;
            }

            foreach ($damages as $damage) {
                DB::table('damage_types')->insert([
                    'service_id' => $serviceId,
                    'name' => $damage['name'],
                    'description' => $damage['name'],
                    'price' => $damage['price'],
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
