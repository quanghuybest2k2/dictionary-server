<?php

namespace App\Repositories\WordRepositoryService;

use App\Models\Word;

class WordRepository implements IWordRepository
{
    public function getRandomWord()
    {
        return Word::inRandomOrder()->first();
    }
    public function search($keyword)
    {
        return Word::where('word_name', 'like', '%' . $keyword . '%')->first();
    }
}
