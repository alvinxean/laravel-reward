<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periods')->insert([
            [
                'name' => 'Periode 1',
                'start_date' => '2025-01-01',
                'end_regist_date' => '2025-01-10',
                'end_date' => '2025-06-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 2',
                'start_date' => '2025-02-01',
                'end_regist_date' => '2025-02-10',
                'end_date' => '2025-07-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 3',
                'start_date' => '2025-03-01',
                'end_regist_date' => '2025-03-10',
                'end_date' => '2025-08-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 4',
                'start_date' => '2025-04-01',
                'end_regist_date' => '2025-04-10',
                'end_date' => '2025-09-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 5',
                'start_date' => '2025-05-01',
                'end_regist_date' => '2025-05-10',
                'end_date' => '2025-10-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 6',
                'start_date' => '2025-06-01',
                'end_regist_date' => '2025-06-10',
                'end_date' => '2025-11-30',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Periode 7',
                'start_date' => '2025-07-01',
                'end_regist_date' => '2025-07-10',
                'end_date' => '2025-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
