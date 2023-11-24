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

    public function displayByTimeWordLookupHistory($user_id, $time);

    public function deleteAllWordLookupHistory($userId);

    public function deleteByIdWordLookupHistory($userId, $id);

    // ====================== TranslateHistory =============================
    public function loadAllTranslateHistory($userId);

    public function searchTranslateHistory($english, $user_id);

    public function createTranslateHistory($data);

    public function displayByTimeTranslateHistory($user_id, $time);

    public function deleteByIdTranslateHistory($userId, $id);

    public function deleteAllTranslateHistory($userId);

    public function deleteAllHistory($userId);
}
