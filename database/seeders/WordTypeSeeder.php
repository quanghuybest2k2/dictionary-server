<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WordTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         *'Động từ'
         *'Tính từ'
         *'Trạng từ'
         *'Giới từ'
         *'Đại từ'
         *'Liên từ'
         *'Thán từ'
         */
        $wordTypes = [
            'Danh từ',
            'Động từ',
            'Tính từ',
            'Trạng từ',
            'Giới từ',
            'Đại từ',
            'Liên từ',
            'Thán từ',
        ];

        DB::table('word_types')->insert(
            array_map(function ($type) {
                return ['type_name' => $type];
            }, $wordTypes)
        );
    }
}
