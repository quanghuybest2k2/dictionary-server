<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * MMT
         * ('Firewall', N'/ˈfaɪə.wɔːl/', '1', 'Barricade', 'Firing')
         * ('ISP', N'/ˌaɪ.esˈpiː/', '1', '', '')
         * ('Download', N'/ˈdaʊn.loʊd/', '1', 'Transfer', 'Upload')
         * ('Web hosting', N'/ˈweb ˌhoʊ.stɪŋ/', '1', 'Hosting services', 'Storage')
         * KTPM
         * ('Variable',N'/ˈver.i.ə.bəl/','2','Varying','Invariable')
         * ('Calculation',N'/ˈkæl.kjə.leɪt/','2','computation', '')
         * ('Function',N'/ˈfʌŋk.ʃən/','2','task', '')
         * ('Horizontal ',N'/ˌhɔːr.ɪˈzɑːn.t̬əl/','2','','Vertical')
         * KHDL
         * ('Queue',N'/kjuː/','3','','')
         * ('Random',N'/ˈræn.dəm/','3','arbitrary','orderly')
         * ('Recovery',N'/rɪˈkʌv.ɚ.i/','3','rehabilitation','loss')
         */
        DB::table('words')->insert(
            [
                [
                    'word_name' => 'Firewall',
                    'pronunciations' => '/ˈfaɪə.wɔːl/',
                    'specialization_id' => '1',
                    'synonymous' => 'Barricade',
                    'antonyms' => 'Firing',
                    'status' => 1,
                ],
                [
                    'word_name' => 'ISP',
                    'pronunciations' => '/ˌaɪ.esˈpiː/',
                    'specialization_id' => '1',
                    'synonymous' => '',
                    'antonyms' => '',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Download',
                    'pronunciations' => '/ˈdaʊn.loʊd/',
                    'specialization_id' => '1',
                    'synonymous' => 'Transfer',
                    'antonyms' => 'Upload',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Web hosting',
                    'pronunciations' => '/ˈweb ˌhoʊ.stɪŋ/',
                    'specialization_id' => '1',
                    'synonymous' => 'Hosting services',
                    'antonyms' => 'Storage',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Variable',
                    'pronunciations' => '/ˈver.i.ə.bəl/',
                    'specialization_id' => '2',
                    'synonymous' => 'Varying',
                    'antonyms' => 'Invariable',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Calculation',
                    'pronunciations' => '/ˌkæl.kjəˈleɪ.ʃən/',
                    'specialization_id' => '2',
                    'synonymous' => 'computation',
                    'antonyms' => '',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Function',
                    'pronunciations' => '/ˈfʌŋk.ʃən/',
                    'specialization_id' => '2',
                    'synonymous' => 'Task',
                    'antonyms' => '',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Horizontal',
                    'pronunciations' => '/ˌhɔːr.ɪˈzɑːn.t̬əl/',
                    'specialization_id' => '2',
                    'synonymous' => '',
                    'antonyms' => 'Vertical',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Queue',
                    'pronunciations' => '/kjuː/',
                    'specialization_id' => '3',
                    'synonymous' => '',
                    'antonyms' => '',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Random',
                    'pronunciations' => '/ˈræn.dəm/',
                    'specialization_id' => '3',
                    'synonymous' => 'arbitrary',
                    'antonyms' => 'orderly',
                    'status' => 1,
                ],
                [
                    'word_name' => 'Recovery',
                    'pronunciations' => '/rɪˈkʌv.ɚ.i/',
                    'specialization_id' => '3',
                    'synonymous' => 'rehabilitation',
                    'antonyms' => 'loss',
                    'status' => 1,
                ],
            ]
        );
    }
}
