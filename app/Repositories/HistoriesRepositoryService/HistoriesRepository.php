<?php

namespace App\Repositories\HistoriesRepositoryService;

use App\Models\TranslateHistory;
use App\Models\WordLookupHistory;
use App\Repositories\HistoriesRepositoryService\IHistoriesRepository;
use Illuminate\Database\Eloquent\Model;

class HistoriesRepository implements IHistoriesRepository
{
    public function checkIfExist(Model $model, $english, $userId)
    {
        return $model::where('english', $english)->where('user_id', $userId)->count();
    }
    public function createWordLookupHistory($data)
    {
        return WordLookupHistory::create($data);
    }
    public function loadAllTranslateHistory($userId)
    {
        return TranslateHistory::where('user_id', $userId)->get();
    }
    public function createTranslateHistory($data)
    {
        return TranslateHistory::create($data);
    }
}
