<?php

namespace Database\Seeders;

use App\Models\Occupation;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupations = [
            ['name' => 'Tidak Bekerja / Ibu Rumah Tangga', 'code' => 'OC-00'],
            ['name' => 'Pegawai Negeri Sipil (PNS)', 'code' => 'OC-01'],
            ['name' => 'TNI / POLRI', 'code' => 'OC-02'],
            ['name' => 'Pegawai Swasta', 'code' => 'OC-03'],
            ['name' => 'BUMN / BUMD', 'code' => 'OC-04'],
            ['name' => 'Wiraswasta / Pengusaha', 'code' => 'OC-05'],
            ['name' => 'Petani / Peternak', 'code' => 'OC-06'],
            ['name' => 'Nelayan', 'code' => 'OC-07'],
            ['name' => 'Buruh / Pekerja Harian Lepas', 'code' => 'OC-08'],
            ['name' => 'Guru / Dosen / Tenaga Pendidik', 'code' => 'OC-09'],
            ['name' => 'Tenaga Medis (Dokter, Perawat, Bidan)', 'code' => 'OC-10'],
            ['name' => 'Pedagang', 'code' => 'OC-11'],
            ['name' => 'Sopir / Driver Online / Kurir', 'code' => 'OC-12'],
            ['name' => 'Pensiunan', 'code' => 'OC-13'],
            ['name' => 'Lainnya', 'code' => 'OC-99'],
        ];

        foreach ($occupations as $occupation) {
            Occupation::updateOrCreate(
                ['code' => $occupation['code']],
                ['name' => $occupation['name'], 'is_active' => true]
            );
        }
    }
}
