<?php

namespace App\Repositories\HotVocabularyRepositoryService;

use App\Models\WordLookupHistory;

class HotVocabularyRepository implements IHotVocabularyRepository
{
    public function getHotVocabulary()
    {
        return  WordLookupHistory::select(
            'english',
            'pronunciations',
            'vietnamese'
        )
            ->selectRaw('COUNT(user_id) AS NumberOfOccurrences')
            ->groupBy('english', 'pronunciations', 'vietnamese')
            ->orderByDesc('NumberOfOccurrences')
            ->limit(8)
            ->get();
    }
}
