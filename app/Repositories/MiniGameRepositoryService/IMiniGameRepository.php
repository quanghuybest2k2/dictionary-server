<?php

namespace App\Repositories\MiniGameRepositoryService;

interface IMiniGameRepository
{
    public function getQuestions($limit, $user_id);
    public function getMoreQuestions($amount);
    public function getRandomWrongAnswers($english, $limit);
}
