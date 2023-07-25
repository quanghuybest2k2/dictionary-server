<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoveTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('love_texts')->insert(
            [
                [
                    'english' => 'My name is Huy',
                    'vietnamese' => 'Tôi tên là Huy',
                    'Note' => 'Khá thú vị',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'english' => 'My name is Tom',
                    'vietnamese' => 'Tôi tên là Tom',
                    'Note' => 'Tom là bạn tôi',
                    'user_id' => '3',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'english' => 'My name is David',
                    'vietnamese' => 'David là anh trai tôi',
                    'Note' => 'Khá thú vị',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
