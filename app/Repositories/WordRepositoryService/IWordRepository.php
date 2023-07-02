<?php

namespace App\Repositories\WordRepositoryService;

interface IWordRepository
{
    public function getAll();
    public function getRandomWord();
    public function search($keyword);
}
