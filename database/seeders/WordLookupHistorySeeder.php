<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WordLookupHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('word_lookup_histories')->insert(
            [
                [
                    'english' => 'Firewall',
                    'pronunciations' => '/ˈfaɪə.wɔːl/',
                    'vietnamese' => 'Tường lửa',
                    'user_id' => '2',
                    'created_at' => Carbon::now(),
                    'updated_at' =>  Carbon::now()
                ],
                [
                    'english' => 'Download',
                    'pronunciations' => '/ˈdaʊn.loʊd/',
                    'vietnamese' => 'Tải xuống',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'english' => 'Queue',
                    'pronunciations' => '/kjuː/',
                    'vietnamese' => 'Hàng đợi',
                    'user_id' => '2',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ]
        );
    }
}
