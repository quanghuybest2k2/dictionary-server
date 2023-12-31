<?php

namespace App\Repositories\MiniGameRepositoryService;

use Illuminate\Support\Facades\DB;

class MiniGameRepository implements IMiniGameRepository
{
    // SELECT english, type_name, vietnamese
    // FROM (
    //     SELECT DISTINCT wlh.english, wt.type_name, wlh.vietnamese
    //     FROM word_lookup_histories wlh 
    //     JOIN words w ON wlh.english = w.word_name
    //     JOIN means m ON w.id = m.word_id
    //     JOIN word_types wt ON m.word_type_id = wt.id
    //     WHERE wlh.user_id = 2 AND wlh.vietnamese = m.means
    // ) AS random
    // ORDER BY RAND() LIMIT 3;

    /**
     * Lấy ngẫu nhiên 10 từ làm câu hỏi trong lịch sử tra từ
     *
     * @param int $limit số lượng lấy
     * @param int $user_id ID người dùng
     * @return void
     */
    public function getQuestions($limit, $user_id)
    {
        return DB::table('word_lookup_histories as wlh')
            ->select('wlh.english', 'wt.type_name', 'wlh.vietnamese')
            ->distinct()
            ->join('words as w', 'wlh.english', '=', 'w.word_name')
            ->join('means as m', 'w.id', '=', 'm.word_id')
            ->join('word_types as wt', 'm.word_type_id', '=', 'wt.id')
            ->where('wlh.user_id', '=', $user_id)
            ->where('wlh.vietnamese', '=', DB::raw('m.means'))
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
    // select word_name, type_name, means
    // from
    // (
    // 	select distinct w.word_name, wt.type_name, m.means 
    // 	from word_types wt, words w, means m
    // 	where w.id = m.word_id and m.word_type_id = wt.id
    // )  as random
    // ORDER BY RAND() LIMIT 3;

    /**
     * Lấy thêm câu hỏi nếu chưa đủ 10 câu
     *
     * @param int $limit
     * @return void
     */
    public function getMoreQuestions($limit)
    {
        return DB::table('words as w')
            ->select('w.word_name', 'wt.type_name', 'm.means')
            ->distinct()
            ->join('means as m', 'w.id', '=', 'm.word_id')
            ->join('word_types as wt', 'm.word_type_id', '=', 'wt.id')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
    // SELECT vietnamese
    // FROM (SELECT DISTINCT vietnamese FROM word_lookup_histories) AS vietnamese
    // WHERE vietnamese NOT IN (SELECT vietnamese FROM word_lookup_histories WHERE english = 'component')
    // ORDER BY RAND()
    // LIMIT 3;

    /**
     * Lấy 3 đáp án sai
     *
     * @param string $english Ngoại trừ từ này
     * @param int $limit giới hạn số lượng record
     * @return void
     */
    public function getRandomWrongAnswers($english, $limit)
    {
        return DB::table(DB::raw('(SELECT DISTINCT vietnamese FROM word_lookup_histories) AS vietnamese'))
            ->select('vietnamese')
            ->whereNotIn('vietnamese', function ($query) use ($english) {
                $query->select('vietnamese')
                    ->from('word_lookup_histories')
                    ->where('english', '=', $english);
            })
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
