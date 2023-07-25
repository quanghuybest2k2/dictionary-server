<?php

namespace App\Repositories\LoveRepositoryService;

use App\Models\LoveText;
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
    public function createLoveTexts($data)
    {
        return LoveText::create($data);
    }
    public function deleteLoveText($english, $user_id)
    {
        return LoveText::where('english', $english)
            ->where('user_id', $user_id)
            ->delete();
    }
}
