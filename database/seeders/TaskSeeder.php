<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projects = DB::table('projects')
            ->whereIn('name', [
                'Website Company Profile',
                'Task Management App',
                'Inventory Management',
            ])
            ->get()
            ->keyBy('name');

        $tasks = [
            [
                'project' => 'Website Company Profile',
                'title' => 'Membuat desain halaman utama',
                'status' => 'completed',
                'due_date' => now()->subDays(3)->toDateString(),
            ],
            [
                'project' => 'Website Company Profile',
                'title' => 'Membuat halaman About',
                'status' => 'in_progress',
                'due_date' => now()->addDays(2)->toDateString(),
            ],
            [
                'project' => 'Website Company Profile',
                'title' => 'Integrasi contact form',
                'status' => 'pending',
                'due_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'project' => 'Task Management App',
                'title' => 'Membuat autentikasi pengguna',
                'status' => 'completed',
                'due_date' => now()->subDay()->toDateString(),
            ],
            [
                'project' => 'Task Management App',
                'title' => 'Membuat fitur CRUD task',
                'status' => 'in_progress',
                'due_date' => now()->addDays(3)->toDateString(),
            ],
            [
                'project' => 'Task Management App',
                'title' => 'Menguji aplikasi',
                'status' => 'pending',
                'due_date' => now()->addDays(7)->toDateString(),
            ],
            [
                'project' => 'Inventory Management',
                'title' => 'Membuat database inventaris',
                'status' => 'completed',
                'due_date' => now()->subDays(2)->toDateString(),
            ],
            [
                'project' => 'Inventory Management',
                'title' => 'Membuat halaman data barang',
                'status' => 'in_progress',
                'due_date' => now()->addDays(4)->toDateString(),
            ],
        ];

        foreach ($tasks as $task) {
            $project = $projects->get($task['project']);

            if (!$project) {
                throw new \RuntimeException(
                    "Project {$task['project']} tidak ditemukan."
                );
            }

            DB::table('tasks')->updateOrInsert(
                [
                    'project_id' => $project->id,
                    'title' => $task['title'],
                ],
                [
                    'status' => $task['status'],
                    'due_date' => $task['due_date'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}