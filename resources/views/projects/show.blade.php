<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->name }} | Detail Proyek</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fb;
            color: #212529;
        }

        .page-container {
            max-width: 1200px;
        }

        .custom-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        }

        .project-header {
            background: linear-gradient(135deg, #ffffff 0%, #f0f5ff 100%);
        }

        .section-title {
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .task-card {
            border: 1px solid #edf0f5;
            border-radius: 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .task-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 5px 16px rgba(15, 23, 42, 0.04);
        }

        .task-description {
            overflow-wrap: anywhere;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .btn {
            border-radius: 10px;
        }

        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 7px 11px;
        }
    </style>
</head>

<body>
    <div class="container page-container py-4 py-lg-5">
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary bg-white">
                &larr; Kembali ke Dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 rounded-4 auto-dismiss-alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 rounded-4 auto-dismiss-alert">
                <strong>Ada yang perlu diperbaiki:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card custom-card project-header mb-4">
            <div class="card-body p-4 p-lg-5">
                <div
                    class="d-flex flex-column flex-md-row
                            justify-content-between align-items-md-start gap-3">

                    <div>
                        <span
                            class="badge bg-primary-subtle text-primary-emphasis
                                     rounded-pill px-3 py-2 mb-3">
                            Detail Proyek
                        </span>

                        <h1 class="fw-bold mb-2">
                            {{ $project->name }}
                        </h1>

                        <p class="text-secondary mb-3">
                            {{ $project->description ?: 'Belum ada deskripsi untuk proyek ini.' }}
                        </p>

                        <div class="small text-secondary">
                            Dibuat pada
                            {{ $project->created_at->format('d M Y') }}
                        </div>
                    </div>

                    <div class="rounded-4 bg-white border p-3 text-center" style="min-width: 115px;">
                        <div class="text-secondary small mb-1">
                            Total Task
                        </div>
                        <div class="fs-2 fw-bold">
                            {{ $tasks->total() }}
                        </div>
                        <div class="text-secondary small">
                            hasil saat ini
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card custom-card mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="d-flex align-items-center justify-content-center
                                bg-primary-subtle text-primary rounded-3 fw-bold"
                        style="width: 44px; height: 44px; flex-shrink: 0;">
                        +
                    </div>

                    <div>
                        <h4 class="section-title mb-1">Tambah Task Baru</h4>
                        <p class="text-secondary small mb-0">
                            Catat pekerjaan yang perlu diselesaikan dalam proyek ini.
                        </p>
                    </div>
                </div>

                <form action="{{ route('tasks.store', $project) }}" method="POST">
                    @csrf

                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-7">
                            <label for="new_task_title" class="form-label fw-semibold">
                                Nama Task
                            </label>

                            <input type="text" name="title" id="new_task_title" class="form-control"
                                placeholder="Contoh: Membuat desain halaman login" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="due_date" class="form-label fw-semibold">
                                Tenggat Waktu
                            </label>

                            <input type="date" name="due_date" id="due_date" class="form-control"
                                value="{{ old('due_date') }}">
                        </div>

                        <div class="col-12 col-sm-6 col-lg-2">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                + Tambah
                            </button>
                        </div>
                    </div>

                    <div class="form-text mt-2">
                        Tenggat waktu boleh dikosongkan jika belum ditentukan.
                    </div>
                </form>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-body p-4 p-lg-5">

                <div
                    class="d-flex flex-column flex-md-row
                            justify-content-between align-items-md-center gap-2 mb-4">
                    <div>
                        <h4 class="section-title mb-1">Daftar Task</h4>
                        <p class="text-secondary small mb-0">
                            Kelola dan pantau progres pekerjaanmu.
                        </p>
                    </div>
                </div>

                <form method="GET" action="{{ route('projects.show', $project) }}"
                    class="bg-light rounded-4 p-3 p-lg-4 mb-4">

                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-lg-6">
                            <label for="task_title_filter" class="form-label fw-semibold small">
                                Cari judul task
                            </label>

                            <input type="text" name="title" id="task_title_filter" class="form-control bg-white"
                                placeholder="Ketik judul task..." value="{{ $filters['title'] ?? '' }}">
                        </div>

                        <div class="col-12 col-sm-8 col-lg-4">
                            <label for="status" class="form-label fw-semibold small">
                                Status task
                            </label>

                            <select name="status" id="status" class="form-select bg-white">
                                <option value="">Semua status</option>

                                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>
                                    Pending
                                </option>

                                <option value="in_progress" @selected(($filters['status'] ?? '') === 'in_progress')>
                                    Dalam Proses
                                </option>

                                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>
                                    Selesai
                                </option>
                            </select>
                        </div>

                        <div class="col-12 col-sm-4 col-lg-2">
                            <button type="submit" class="btn btn-primary w-100">
                                Cari Task
                            </button>
                        </div>
                    </div>

                    @if (request()->filled('title') || request()->filled('status'))
                        <div class="mt-3">
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-secondary">
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>

                <div class="d-flex flex-column gap-3">
                    @forelse ($tasks as $task)
                        <div class="task-card p-3 p-lg-4">
                            <div
                                class="d-flex flex-column flex-lg-row
                                        justify-content-between gap-3">

                                <div class="grow">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <h5 class="fw-bold mb-0 task-description">
                                            {{ $task->title }}
                                        </h5>

                                        @if ($task->due_date && $task->due_date->lt(today()) && $task->status !== 'completed')
                                            <span
                                                class="badge bg-danger-subtle text-danger-emphasis
                                                         rounded-pill status-badge">
                                                Terlambat
                                            </span>
                                        @endif
                                    </div>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                        <span class="text-secondary small">
                                            Status:
                                        </span>

                                        @if ($task->status === 'pending')
                                            <span
                                                class="badge bg-secondary-subtle text-secondary-emphasis
                                                         rounded-pill status-badge">
                                                Pending
                                            </span>
                                        @elseif ($task->status === 'in_progress')
                                            <span
                                                class="badge bg-primary-subtle text-primary-emphasis
                                                         rounded-pill status-badge">
                                                Dalam Proses
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-success-subtle text-success-emphasis
                                                         rounded-pill status-badge">
                                                Selesai
                                            </span>
                                        @endif
                                    </div>

                                    <div class="small text-secondary">
                                        <span class="fw-semibold">Tenggat:</span>
                                        {{ $task->due_date?->format('d M Y') ?? 'Belum ditentukan' }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-start shrink-0">
                                    @if ($task->status === 'pending')
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="status" value="in_progress">

                                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                                Mulai Kerjakan &rarr;
                                            </button>
                                        </form>
                                    @elseif ($task->status === 'in_progress')
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="status" value="completed">

                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success small fw-semibold">
                                            &#10003; Task Selesai
                                        </span>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="fs-1 mb-3">📋</div>

                            <h5 class="fw-bold">Task tidak ditemukan</h5>

                            <p class="text-secondary mb-3">
                                Tidak ada task yang cocok dengan pencarian atau filter kamu.
                            </p>

                            @if (request()->filled('title') || request()->filled('status'))
                                <a href="{{ route('projects.show', $project) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    Tampilkan Semua Task
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($tasks->hasPages())
                    <div class="border-top mt-4 pt-4">
                        <div
                            class="d-flex flex-column flex-md-row
                                    justify-content-between align-items-md-center gap-3">

                            <small class="text-secondary">
                                Menampilkan {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}
                                dari {{ $tasks->total() }} task
                            </small>

                            <div>
                                {{ $tasks->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.auto-dismiss-alert').delay(3000).fadeOut(500, function() {
                $(this).remove();
            });
        });
    </script>
</body>

</html>