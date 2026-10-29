<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
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

        $activities = [];

        foreach ($projects as $project) {
            $activities[] = [
                'user_id' => $project->user_id,
                'action' => 'PROJECT_CREATED',
                'subject_type' => 'App\\Models\\Project',
                'subject_id' => $project->id,
                'payload' => json_encode([
                    'name' => $project->name,
                    'project_id' => $project->id,
                ]),
                'created_at' => now()->subDays(5),
            ];
        }

        $tasks = DB::table('tasks')
            ->whereIn('project_id', $projects->pluck('id'))
            ->get();

        foreach ($tasks as $task) {
            $project = $projects->firstWhere(
                'id',
                $task->project_id
            );

            if (!$project) {
                continue;
            }

            $activities[] = [
                'user_id' => $project->user_id,
                'action' => 'TASK_CREATED',
                'subject_type' => 'App\\Models\\Task',
                'subject_id' => $task->id,
                'payload' => json_encode([
                    'project_name' => $project->name,
                    'task_name' => $task->title,
                    'old_status' => null,
                    'new_status' => $task->status,
                ]),
                'created_at' => now()->subDays(3),
            ];

            if ($task->status === 'completed') {
                $activities[] = [
                    'user_id' => $project->user_id,
                    'action' => 'TASK_STATUS_UPDATED',
                    'subject_type' => 'App\\Models\\Task',
                    'subject_id' => $task->id,
                    'payload' => json_encode([
                        'project_name' => $project->name,
                        'task_name' => $task->title,
                        'old_status' => 'in_progress',
                        'new_status' => 'completed',
                    ]),
                    'created_at' => now()->subDay(),
                ];
            }
        }

        DB::table('activity_logs')->insert($activities);
    }
}