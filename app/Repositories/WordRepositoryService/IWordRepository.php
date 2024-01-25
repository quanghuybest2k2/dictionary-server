<?php

namespace App\Repositories\WordRepositoryService;

interface IWordRepository
{
    public function getAll();
    public function getUnApproved();
    public function getSuggest($specializationId);
    public function getRandomWord();
    public function searchByKeyword($keyword);
    public function createWord(array $data): array;
}
