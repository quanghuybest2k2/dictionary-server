<?php

namespace App\Repositories\UserRepositoryService;

use App\Models\User;

interface IUserRepository
{
    public function createUser(array $data);
    public function getUserById(string $id);
    public function getUserByEmail(string $email);
    public function deleteUserTokens($userId);
    public function deleteUser($userId);
}
