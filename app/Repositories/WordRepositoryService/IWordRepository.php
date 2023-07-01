<?php

namespace App\Repositories\WordRepositoryService;

interface IWordRepository
{
    public function getRandomWord();
    public function search($keyword);
}
