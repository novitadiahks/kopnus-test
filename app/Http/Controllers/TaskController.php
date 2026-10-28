<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function store(Request $request, Project $project)
    {
        Gate::authorize('view', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ], [
            'title.required' => 'Nama task wajib diisi.',
            'title.max' => 'Nama task maksimal 255 karakter.',
            'due_date.date' => 'Format tanggal tenggat tidak valid.',
        ]);

        DB::transaction(function () use ($project, $validated) {
            $task = $project->tasks()->create([
                'title' => $validated['title'],
                'due_date' => $validated['due_date'] ?? null,
                'status' => 'pending',
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'TASK_CREATED',
                'subject_type' => Task::class,
                'subject_id' => $task->id,
                'payload' => [
                    'title' => $task->title,
                    'project_id' => $project->id,
                    'status' => $task->status,
                ],
            ]);
        });

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task berhasil ditambahkan.');
    }

    public function updateStatus(
        Request $request,
        Task $task,
        TaskService $taskService
    ) {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,completed'],
        ], [
            'status.required' => 'Status baru wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        $taskService->updateStatus(
            $task,
            $validated['status']
        );

        return back()->with(
            'success',
            'Status task berhasil diperbarui.'
        );
    }

    public function updateStatusJson(
        Request $request,
        Task $task,
        TaskService $taskService
    ) {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', 'in:in_progress,completed'],
        ], [
            'status.required' => 'Status baru wajib dipilih.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ]);

        $updatedTask = $taskService->updateStatus(
            $task,
            $validated['status']
        );

        return response()->json([
            'message' => 'Status task berhasil diperbarui.',
            'task' => [
                'id' => $updatedTask->id,
                'title' => $updatedTask->title,
                'status' => $updatedTask->status,
            ],
        ]);
    }
}
