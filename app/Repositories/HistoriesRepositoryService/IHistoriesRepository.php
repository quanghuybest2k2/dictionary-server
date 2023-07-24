<?php

namespace App\Repositories\HistoriesRepositoryService;

use Illuminate\Database\Eloquent\Model;

interface IHistoriesRepository
{
    public function checkIfExist(Model $model, $english, $userId);
    public function loadAllTranslateHistory($userId);
    public function createWordLookupHistory($data);
    public function createTranslateHistory($data);
    public function deleteAllTranslateHistory($userId);
    public function deleteByIdTranslateHistory($userId, $id);
}
