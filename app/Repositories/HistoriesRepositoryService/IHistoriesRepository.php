<?php

namespace App\Repositories\HistoriesRepositoryService;

use Illuminate\Database\Eloquent\Model;

interface IHistoriesRepository
{
    public function checkIfExist(Model $model, $english, $userId);
    // ====================== WordLookupHistory ============================
    public function createWordLookupHistory($data);
    public function getWordLookupHistory($user_id);
    public function searchWordLookupHistory($english, $user_id);

    // ====================== TranslateHistory =============================
    public function loadAllTranslateHistory($userId);
    public function createTranslateHistory($data);
    public function deleteAllTranslateHistory($userId);
    public function deleteByIdTranslateHistory($userId, $id);
}
