<?php

namespace App\Repositories\UserRepositoryService;


interface IUserRepository
{
    public function createUser(array $data);

    public function getUserById(string $id);

    public function getUserByEmail(string $email);

    public function UpdateUser($userId, array $data);

    public function deleteUserTokens($userId);

    public function deleteUser($userId);
}
