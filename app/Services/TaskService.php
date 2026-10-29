<?php

namespace App\Services;

use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TaskService
{
    public function updateStatus(Task $task, string $newStatus): Task
    {
        return DB::transaction(function () use ($task, $newStatus) {
            $lockedTask = Task::whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            $nextStatus = [
                'pending' => 'in_progress',
                'in_progress' => 'completed',
            ];

            $oldStatus = $lockedTask->status;

            if (
                !isset($nextStatus[$oldStatus]) ||
                $nextStatus[$oldStatus] !== $newStatus
            ) {
                throw ValidationException::withMessages([
                    'status' => 'Status hanya bisa berubah dari pending ke in_progress, lalu ke completed.',
                ]);
            }

            $lockedTask->update([
                'status' => $newStatus,
            ]);


            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'TASK_STATUS_UPDATED',
                'subject_type' => Task::class,
                'subject_id' => $lockedTask->id,
                'payload' => [
                    'project_name' => $lockedTask->project->name,
                    'task_name' => $lockedTask->title,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ],
            ]);


            return $lockedTask;
        });
    }
}
