<?php

namespace App\Repositories\HistoriesRepositoryService;

use Illuminate\Database\Eloquent\Model;

interface IHistoriesRepository
{
    public function checkIfExist(Model $model, $english, $userId);
    public function createWordLookupHistory($data);
}
