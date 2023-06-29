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

    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function deleteUserTokens($userId)
    {
        return User::find($userId)->tokens()->delete();
    }
}
