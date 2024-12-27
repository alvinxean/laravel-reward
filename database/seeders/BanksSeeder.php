<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert([
            [
                'name' => 'Tidak tersedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BCA (Bank Central Asia)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BRI (Bank Rakyat Indonesia)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BNI (Bank Negara Indonesia)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BSI (Bank Syariah Indonesia)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sinarmas (Bank Sinarmas)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mandiri (Bank Mandiri)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Jatim',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
