<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Novita',
                'email' => 'novita@example.com',
            ],
            [
                'name' => 'Budi',
                'email' => 'budi@example.com',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}