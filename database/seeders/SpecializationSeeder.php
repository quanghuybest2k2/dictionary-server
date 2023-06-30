<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Specialization::factory()->count(5)->create();
        DB::table('specializations')->insert(
            [
                [
                    'specialization_name' => 'Mạng máy tính',
                ],
                [
                    'specialization_name' => 'Kỹ thuật phần mềm',
                ],
                [
                    'specialization_name' => 'Khoa học dữ liệu',
                ],
            ]
        );
    }
}
