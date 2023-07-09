<?php

namespace App\Repositories\LoveRepositoryService;

interface ILoveRepository
{
    public function createLoveVocabularies($data);
    public function delete($english, $user_id);
}
