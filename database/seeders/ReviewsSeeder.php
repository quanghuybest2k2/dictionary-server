<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('reviews')->delete();

        \DB::table('reviews')->insert(array(
            0 =>
            array(
                'id' => 1,
                'user_id' => 2,
                'rating' => 5,
                'comment' => 'Ứng dụng tốt quá! rất ứng ý.',
                'created_at' => '2024-01-24 23:05:40',
                'updated_at' => '2024-01-24 23:05:40',
            ),
            1 =>
            array(
                'id' => 2,
                'user_id' => 3,
                'rating' => 5,
                'comment' => 'Tìm kiếm chưa được mượt lắm.',
                'created_at' => '2024-01-24 23:05:44',
                'updated_at' => '2024-01-24 23:05:44',
            ),
        ));
    }
}
