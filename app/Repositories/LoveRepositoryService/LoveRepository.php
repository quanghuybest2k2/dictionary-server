<?php

namespace App\Repositories\LoveRepositoryService;

use App\Models\LoveVocabulary;

class LoveRepository implements ILoveRepository
{
    public function createLoveVocabularies($data)
    {
        return LoveVocabulary::create($data);
    }
    public function delete($english, $user_id)
    {
        return LoveVocabulary::where('english', $english)
            ->where('user_id', $user_id)
            ->delete();
    }
}
