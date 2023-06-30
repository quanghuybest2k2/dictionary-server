<?php

namespace App\Repositories\MeansRepositoryService;

interface IMeansRepository
{
    public function findByWordId($wordId);
}
