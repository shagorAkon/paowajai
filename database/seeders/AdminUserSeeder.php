<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'nisharulnirob@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('nirob@9564'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Super Admin');
    }
}
