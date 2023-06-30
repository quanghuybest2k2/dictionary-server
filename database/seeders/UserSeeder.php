<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // User::factory(10)->create();
        DB::table('users')->insert(
            [
                [
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'gender' => '1',
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'role_as' => 1,
                ],
                [
                    'name' => 'Đoàn Quang Huy',
                    'email' => 'quanghuybest@gmail.com',
                    'gender' => '1',
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'role_as' => 0,
                ],
                [
                    'name' => 'Trần Ngọc Hân',
                    'email' => 'tranngochan@gmail.com',
                    'gender' => '2',
                    'email_verified_at' => now(),
                    'password' => Hash::make('12345678'),
                    'role_as' => 0,
                ],
            ]
        );
    }
}
