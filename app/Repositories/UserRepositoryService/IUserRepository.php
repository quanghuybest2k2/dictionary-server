<?php

namespace App\Repositories\UserRepositoryService;

use App\Models\User;

interface IUserRepository
{
    public function createUser(array $data);
    public function getUserByEmail(string $email);
    public function deleteUserTokens($userId);
}
