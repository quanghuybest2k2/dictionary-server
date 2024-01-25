<?php

namespace App\Repositories\WordTypeRepositoryService;

interface IWordTypeRepository
{
    public function getAll();
    public function find($id);
}
