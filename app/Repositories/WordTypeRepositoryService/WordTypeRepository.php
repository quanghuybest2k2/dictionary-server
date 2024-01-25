<?php

namespace App\Repositories\WordTypeRepositoryService;

use App\Models\WordType;

class WordTypeRepository implements IWordTypeRepository
{
    /**
     * Lấy tất cả từ loại.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return WordType::all();
    }
    public function find($id)
    {
        return WordType::find($id);
    }
}
