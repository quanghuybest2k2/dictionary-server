<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoveVocabularySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('love_vocabularies')->insert(
            [
                [
                    'english' => 'Firewall',
                    'pronunciations' => '/ˈfaɪə.wɔːl/',
                    'vietnamese' => 'Tường lửa',
                    'Note' => 'tường lửa là tường lửa',
                    'user_id' => '2',
                    'created_at' => Carbon::now(),
                    'updated_at' =>  Carbon::now()
                ],
                [
                    'english' => 'Download',
                    'pronunciations' => '/ˈdaʊn.loʊd/',
                    'vietnamese' => 'Tải xuống',
                    'Note' => 'Tải xuống là tải xuống',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'english' => 'Queue',
                    'pronunciations' => '/kjuː/',
                    'vietnamese' => 'Hàng đợi',
                    'Note' => 'Hàng đợi là hàng đợi',
                    'user_id' => '3',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]
        );
    }
}
