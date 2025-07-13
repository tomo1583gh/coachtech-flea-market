<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        if (!User::where('email', 'test@example.com')->exists()) {
            user::create([
                'name' => 'テストユーザー',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
