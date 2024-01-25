<?php

namespace App\Repositories\MeansRepositoryService;

interface IMeansRepository
{
    public function findByWordId($wordId);
    public function createMean(array $data): array;
}
