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
    // ====================== WordLookupHistory ============================
    public function createWordLookupHistory($data)
    {
        return WordLookupHistory::create($data);
    }
    public function getWordLookupHistory($user_id)
    {
        return WordLookupHistory::where('user_id', $user_id)->get();
    }
    // ====================== TranslateHistory =============================
    public function loadAllTranslateHistory($userId) // nó là getTranslateHistory đó :))
    {
        return TranslateHistory::where('user_id', $userId)->get();
    }
    public function createTranslateHistory($data)
    {
        return TranslateHistory::create($data);
    }
    public function deleteAllTranslateHistory($userId)
    {
        return TranslateHistory::where('user_id', $userId)->delete();
    }
    public function deleteByIdTranslateHistory($userId, $id)
    {
        return TranslateHistory::where('user_id', $userId)
            ->where('id', $id)
            ->delete();
    }
}
