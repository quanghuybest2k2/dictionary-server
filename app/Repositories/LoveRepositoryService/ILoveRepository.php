<?php

namespace App\Repositories\LoveRepositoryService;

interface ILoveRepository
{
    public function createLoveVocabularies($data);
    public function delete($english, $user_id);
    public function TotalLoveItemOfUser($user_id): int;
    public function createLoveTexts($data);
    public function deleteLoveText($english, $user_id);
}
