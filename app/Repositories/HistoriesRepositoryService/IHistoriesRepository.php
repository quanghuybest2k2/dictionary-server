<?php

namespace App\Repositories\HistoriesRepositoryService;

use App\Models\WordLookupHistory;

interface IHistoriesRepository
{
    public function checkIfExist($keyword, $userId);
    public function createWordLookupHistory($data);
}
