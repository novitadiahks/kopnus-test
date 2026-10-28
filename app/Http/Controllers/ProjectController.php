<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TaskFilterRequest;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{


    public function index()
    {
        Gate::authorize('viewAny', Project::class);

        $projects = auth()->user()->projects()
            ->withCount([
                'tasks as pending_tasks_count' => fn($query) =>
                $query->where('status', 'pending'),

                'tasks as in_progress_tasks_count' => fn($query) =>
                $query->where('status', 'in_progress'),

                'tasks as completed_tasks_count' => fn($query) =>
                $query->where('status', 'completed'),
            ])
            ->latest()
            ->get();

        return view('dashboard', compact('projects'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama proyek wajib diisi.',
            'name.max' => 'Nama proyek maksimal 255 karakter.',
            'description.string' => 'Deskripsi proyek harus berupa teks.',
        ]);

        DB::transaction(function () use ($validated) {
            $project = auth()->user()->projects()->create($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'PROJECT_CREATED',
                'subject_type' => Project::class,
                'subject_id' => $project->id,
                'payload' => [
                    'name' => $project->name,
                ],
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Proyek berhasil dibuat.');
    }



    public function show(
        TaskFilterRequest $request,
        Project $project
    ) {
        Gate::authorize('view', $project);

        $filters = $request->validated();

        $tasks = $project->tasks()
            ->when(
                $filters['title'] ?? null,
                fn($query, $title) =>
                $query->where('title', 'like', '%' . $title . '%')
            )
            ->when(
                $filters['status'] ?? null,
                fn($query, $status) =>
                $query->where('status', $status)
            )
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('projects.show', compact(
            'project',
            'tasks',
            'filters'
        ));
    }



    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        DB::transaction(function () use ($project) {
            // Simpan informasi sebelum proyek dihapus secara logis.
            $projectId = $project->id;
            $projectName = $project->name;

            $project->delete();

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'PROJECT_DELETED',
                'subject_type' => Project::class,
                'subject_id' => $projectId,
                'payload' => [
                    'name' => $projectName,
                    'project_id' => $projectId,
                ],
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Proyek berhasil dihapus.');
    }
}
