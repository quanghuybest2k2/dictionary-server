<?php

namespace App\Repositories\WordTypeRepositoryService;

use App\Models\WordType;

class WordTypeRepository implements IWordTypeRepository
{
    public function find($id)
    {
        return WordType::find($id);
    }
}
