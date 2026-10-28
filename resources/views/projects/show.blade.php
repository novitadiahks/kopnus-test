<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary mb-4">
            &larr; Kembali ke Dashboard
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2>{{ $project->name }}</h2>
                <p class="text-muted mb-0">
                    {{ $project->description ?: 'Tidak ada deskripsi.' }}
                </p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4 class="mb-3">Tambah Task</h4>

                <form action="{{ route('tasks.store', $project) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">
                            Nama Task
                        </label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">
                            Tenggat Waktu (Opsional)
                        </label>
                        <input type="date" name="due_date" id="due_date" class="form-control"
                            value="{{ old('due_date') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Tambah Task
                    </button>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <form method="GET" action="{{ route('projects.show', $project) }}" class="card card-body mb-3">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">
                                Cari judul task
                            </label>

                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="Masukkan judul task..." value="{{ $filters['title'] ?? '' }}">
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">
                                Filter status
                            </label>

                            <select name="status" id="status" class="form-select">
                                <option value="">Semua status</option>

                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>
                                    Pending
                                </option>

                                <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>
                                    In Progress
                                </option>

                                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>
                                    Completed
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                Cari
                            </button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-secondary">
                            Reset filter
                        </a>
                    </div>
                </form>

                <h4 class="mb-3">Daftar Task</h4>

                @forelse ($tasks as $task)
                    <div class="border rounded p-3 mb-3">
                        <h5>{{ $task->title }}</h5>

                        @if ($task->due_date && $task->due_date->lt(today()) && $task->status !== 'completed')
                            <span class="badge bg-danger">Terlambat</span>
                        @endif

                        <p class="mb-1">
                            Status:
                            <span class="badge bg-secondary">
                                {{ $task->status }}
                            </span>
                        </p>

                        @if ($task->status === 'pending')
                            <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="mt-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_progress">

                                <button type="submit" class="btn btn-primary btn-sm">
                                    Mulai Kerjakan
                                </button>
                            </form>
                        @elseif ($task->status === 'in_progress')
                            <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="mt-3">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">

                                <button type="submit" class="btn btn-success btn-sm">
                                    Tandai Selesai
                                </button>
                            </form>
                        @else
                            <span class="badge bg-success mt-2">Task Selesai</span>
                        @endif

                        <p class="text-muted mb-0">
                            Tenggat:
                            {{ $task->due_date?->format('d/m/Y') ?? 'Belum ditentukan' }}
                        </p>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        Tidak ada task yang cocok dengan pencarian atau filter.
                    </p>
                @endforelse

                {{ $tasks->links() }}
            </div>
        </div>
    </div>

</body>

</html>
