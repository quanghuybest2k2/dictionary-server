<?php

namespace App\Repositories\WordRepositoryService;

use App\Models\Word;

class WordRepository implements IWordRepository
{
    public function getRandomWord()
    {
        return Word::inRandomOrder()->first();
    }
}
