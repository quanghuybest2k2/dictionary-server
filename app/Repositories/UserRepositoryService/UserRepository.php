<?php

namespace App\Repositories\UserRepositoryService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function createUser(array $data)
    {
        return User::create($data);
    }
    public function getUserById(string $id)
    {
        return User::where('id', $id)->first();
    }
    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function deleteUserTokens($userId)
    {
        return User::find($userId)->tokens()->delete();
    }
    public function deleteUser($userId)
    {
        return User::find($userId)->delete();
    }
}
