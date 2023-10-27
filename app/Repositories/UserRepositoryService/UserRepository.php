<?php

namespace App\Repositories\UserRepositoryService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function createUser(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password'])
        ]);

        // Tạo token cho người dùng
        $token = $user->createToken($user->email . '_Token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function getUserById(string $id)
    {
        return User::where('id', $id)->first();
    }

    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function UpdateUser($userId, array $data)
    {
        $user = $this->getUserById($userId);
        $user->update($data);
        return $user;
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
