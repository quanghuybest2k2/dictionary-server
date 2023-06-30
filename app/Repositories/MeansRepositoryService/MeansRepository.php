<?php

namespace App\Repositories\MeansRepositoryService;

use App\Models\Means;

class MeansRepository implements IMeansRepository
{
    public function findByWordId($wordId)
    {
        return Means::where('word_id', $wordId)->first();
    }
}
