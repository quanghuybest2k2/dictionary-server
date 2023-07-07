<?php

namespace App\Repositories\HistoriesRepositoryService;

use App\Models\WordLookupHistory;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;

class HistoriesRepository implements IHistoriesRepository
{
    public function checkIfExist($english, $userId)
    {
        return WordLookupHistory::where('english', $english)->where('user_id', $userId)->count();
    }
    public function createWordLookupHistory($data)
    {
        return WordLookupHistory::create($data);
    }
}
