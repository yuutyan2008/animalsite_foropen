<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        // 採用担当者向けデモアカウント（管理者）
        User::firstOrCreate(
            ['email' => 'demo-admin@example.com'],
            [
                'name'     => 'デモ管理者',
                'password' => Hash::make('demo-admin-password'),
                'role'     => User::ROLE_ADMIN,
            ]
        );

        // 採用担当者向けデモアカウント（一般ユーザー）
        User::firstOrCreate(
            ['email' => 'demo-user@example.com'],
            [
                'name'     => 'デモユーザー',
                'password' => Hash::make('demo-user-password'),
                'role'     => User::ROLE_USER,
            ]
        );
    }
}
