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

    public function searchWordLookupHistory($english, $user_id)
    {
        return WordLookupHistory::where('english', $english)
            ->where('user_id', $user_id)
            ->get();
    }

    public function displayByTimeWordLookupHistory($user_id, $time)
    {
        //SELECT * from word_lookup_histories WHERE user_id = 2 and created_at LIKE '%2023-11-13%';
        return WordLookupHistory::where('user_id', $user_id)
            ->where('created_at', 'LIKE', '%' . $time . '%')
            ->get();
    }
    public function deleteByIdWordLookupHistory($userId, $id)
    {
        return WordLookupHistory::where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }
    public function deleteAllWordLookupHistory($userId)
    {
        return WordLookupHistory::where('user_id', $userId)->delete();
    }

    // ====================== TranslateHistory =============================
    public function loadAllTranslateHistory($userId) // nó là getTranslateHistory đó :))
    {
        return TranslateHistory::where('user_id', $userId)->get();
    }

    /*
        SELECT *
        FROM LichSuDich
        WHERE TiengAnh LIKE CONCAT('%', 'variable', '%') AND IDTK = 2;
         */
    public function searchTranslateHistory($english, $user_id)
    {
        return TranslateHistory::where('english', 'LIKE', '%' . $english . '%')
            ->where('user_id', $user_id)
            ->get();
    }

    public function createTranslateHistory($data)
    {
        return TranslateHistory::create($data);
    }

    public function displayByTimeTranslateHistory($user_id, $time)
    {
        return TranslateHistory::where('user_id', $user_id)
            ->where('created_at', 'LIKE', '%' . $time . '%')
            ->get();
    }

    public function deleteByIdTranslateHistory($userId, $id)
    {
        return TranslateHistory::where('id', $id)
            ->where('user_id', $userId)
            ->delete();
    }

    public function deleteAllTranslateHistory($userId)
    {
        return TranslateHistory::where('user_id', $userId)->delete();
    }

    public function deleteAllHistory($id)
    {
        return $this->deleteAllWordLookupHistory($id) + $this->deleteAllTranslateHistory($id);
    }
}
