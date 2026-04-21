<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Hashファサードでmakeメソッドを呼び出すことにより、パスワードをハッシュすることができる
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. システム管理者: role = 1
        User::firstOrCreate(
            ['email' => 'admin1@example.com'],
            [
                'name'     => 'admin1',
                'password' => Hash::make('password'),
                'role'     => '1',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin2@example.com'],
            [
                'name'     => 'admin2',
                'password' => Hash::make('password'),
                'role'     => '1',
            ]
        );
    }
}
