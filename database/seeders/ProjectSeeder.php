<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $novita = DB::table('users')
            ->where('email', 'novita@example.com')
            ->first();

        $budi = DB::table('users')
            ->where('email', 'budi@example.com')
            ->first();

        if (!$novita || !$budi) {
            throw new \RuntimeException(
                'Jalankan UserSeeder terlebih dahulu.'
            );
        }

        $projects = [
            [
                'user_id' => $novita->id,
                'name' => 'Website Company Profile',
                'description' => 'Pengembangan website company profile.',
            ],
            [
                'user_id' => $novita->id,
                'name' => 'Task Management App',
                'description' => 'Aplikasi untuk mengelola project dan task.',
            ],
            [
                'user_id' => $budi->id,
                'name' => 'Inventory Management',
                'description' => 'Sistem pengelolaan inventaris barang.',
            ],
        ];

        foreach ($projects as $project) {
            DB::table('projects')->updateOrInsert(
                [
                    'user_id' => $project['user_id'],
                    'name' => $project['name'],
                ],
                [
                    'description' => $project['description'],
                    'updated_at' => now(),
                    'created_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }
    }
}