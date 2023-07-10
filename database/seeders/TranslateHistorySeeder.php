<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;

class TranslateHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('translate_histories')->insert(
            [
                [
                    'english' => 'My name is Huy',
                    'vietnamese' => 'Tôi tên Huy',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'english' => 'I love you',
                    'vietnamese' => 'Anh Yêu Em',
                    'user_id' => '2',
                    'created_at' => Carbon::yesterday()->hour(13)->minute(2)->second(2),
                    'updated_at' => Carbon::yesterday()->hour(13)->minute(2)->second(2),
                ],
                [
                    'english' => 'I want to study well',
                    'vietnamese' => 'tôi muốn học tốt',
                    'user_id' => '3',
                    'created_at' => Carbon::yesterday()->hour(20)->minute(5)->second(20),
                    'updated_at' => Carbon::yesterday()->hour(20)->minute(5)->second(20),
                ],
                [
                    'english' => 'Faculty of Information Technology',
                    'vietnamese' => 'Khoa Công nghệ thông tin',
                    'user_id' => '3',
                    'created_at' => Carbon::yesterday()->hour(19)->minute(4)->second(45),
                    'updated_at' => Carbon::yesterday()->hour(19)->minute(4)->second(45),
                ],
            ]
        );
    }
}
