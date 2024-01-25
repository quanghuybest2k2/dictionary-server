<?php

namespace App\Repositories\UserRepositoryService;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements IUserRepository
{
    public function createUser(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password'])
        ]);

        // Tạo token cho người dùng
        $token = $user->createToken($user->email . '_Token')->plainTextToken;

        return array_merge($user->toArray(), ['token' => $token]);
    }
    public function login(array $data): array
    {
        $user = $this->getUserByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception("Thông tin không hợp lệ!");
        }
        if ($user->role_as == 1) { // admin
            $role = 'admin';
            $token = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
        } else {
            $role = '';
            $token = $user->createToken($user->email . '_Token', [''])->plainTextToken;
        }
        return array_merge($user->toArray(), ['token' => $token, 'role' => $role]);
    }
    public function getUserById(string $id): User|null
    {
        return User::where('id', $id)->first();
    }

    public function getUserByEmail(string $email): User|null
    {
        return User::where('email', $email)->first();
    }

    public function UpdateUser($userId, array $data): User|null
    {
        $user = $this->getUserById($userId);

        $user->update($data);

        return $user;
    }

    public function deleteUserTokens($userId): bool
    {
        $user = $this->getUserById($userId);

        $deletedTokens = $user->tokens()->delete();

        return $deletedTokens > 0;
    }

    public function deleteUser($userId): bool
    {
        try {
            $user = $this->getUserById($userId);

            if ($user) {
                // Xóa tất cả các token của người dùng trước khi xóa tài khoản
                $this->deleteUserTokens($userId);

                // Xóa tài khoản người dùng
                $isDeleted = $user->delete();

                if ($isDeleted) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
