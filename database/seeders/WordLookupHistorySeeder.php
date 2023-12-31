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
        \DB::table('word_lookup_histories')->delete();

        \DB::table('word_lookup_histories')->insert(array(
            0 =>
            array(
                'id' => 1,
                'english' => 'Firewall',
                'pronunciations' => '/ˈfaɪə.wɔːl/',
                'vietnamese' => 'Tường lửa',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:20:12',
                'updated_at' => '2023-12-30 14:20:12',
            ),
            1 =>
            array(
                'id' => 2,
                'english' => 'Download',
                'pronunciations' => '/ˈdaʊn.loʊd/',
                'vietnamese' => 'Tải xuống',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:20:12',
                'updated_at' => '2023-12-30 14:20:12',
            ),
            2 =>
            array(
                'id' => 3,
                'english' => 'Queue',
                'pronunciations' => '/kjuː/',
                'vietnamese' => 'Hàng đợi',
                'user_id' => 3,
                'created_at' => '2023-12-30 14:20:12',
                'updated_at' => '2023-12-30 14:20:12',
            ),
            3 =>
            array(
                'id' => 4,
                'english' => 'Firewall',
                'pronunciations' => '/ˈfaɪə.wɔːl/',
                'vietnamese' => 'Tường lửa',
                'user_id' => 3,
                'created_at' => '2023-12-30 14:20:12',
                'updated_at' => '2023-12-30 14:20:12',
            ),
            4 =>
            array(
                'id' => 5,
                'english' => 'ISP',
                'pronunciations' => '/ˌaɪ.esˈpiː/',
                'vietnamese' => 'Nhà phân phối dịch vụ internet',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:21:00',
                'updated_at' => '2023-12-30 14:21:00',
            ),
            5 =>
            array(
                'id' => 6,
                'english' => 'Horizontal',
                'pronunciations' => '/ˌhɔːr.ɪˈzɑːn.t̬əl/',
                'vietnamese' => 'Ngang, đường ngang',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:21:26',
                'updated_at' => '2023-12-30 14:21:26',
            ),
            6 =>
            array(
                'id' => 7,
                'english' => 'Web hosting',
                'pronunciations' => '/ˈweb ˌhoʊ.stɪŋ/',
                'vietnamese' => 'Dịch vụ lưu trữ website',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:21:45',
                'updated_at' => '2023-12-30 14:21:45',
            ),
            7 =>
            array(
                'id' => 8,
                'english' => 'Calculation',
                'pronunciations' => '/ˌkæl.kjəˈleɪ.ʃən/',
                'vietnamese' => 'Tính toán',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:22:06',
                'updated_at' => '2023-12-30 14:22:06',
            ),
            8 =>
            array(
                'id' => 9,
                'english' => 'Recovery',
                'pronunciations' => '/rɪˈkʌv.ɚ.i/',
                'vietnamese' => 'Khôi phục',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:22:36',
                'updated_at' => '2023-12-30 14:22:36',
            ),
            9 =>
            array(
                'id' => 10,
                'english' => 'Function',
                'pronunciations' => '/ˈfʌŋk.ʃən/',
                'vietnamese' => 'Hàm, chức năng',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:22:47',
                'updated_at' => '2023-12-30 14:22:47',
            ),
            10 =>
            array(
                'id' => 11,
                'english' => 'Variable',
                'pronunciations' => '/ˈver.i.ə.bəl/',
                'vietnamese' => 'Biến',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:28:18',
                'updated_at' => '2023-12-30 14:28:18',
            ),
            11 =>
            array(
                'id' => 12,
                'english' => 'Variable',
                'pronunciations' => '/ˈver.i.ə.bəl/',
                'vietnamese' => 'Thay đổi',
                'user_id' => 2,
                'created_at' => '2023-12-30 14:28:21',
                'updated_at' => '2023-12-30 14:28:21',
            ),
        ));
    }
}
