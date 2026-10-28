<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar bg-white border-bottom">
        <div class="container">
            <span class="navbar-brand fw-bold">Project Manager</span>

            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary">
                    {{ auth()->user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-primary">
            Riwayat Aktivitas
        </a>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-1">Proyek Saya</h1>
                <p class="text-secondary mb-0">
                    Kelola semua proyekmu di sini.
                </p>
            </div>
        </div>

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

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Buat Proyek Baru</h5>

                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama proyek</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name') }}" maxlength="255" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            Deskripsi (opsional)
                        </label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Tambah Proyek
                    </button>
                </form>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Daftar Proyek</h5>

        <div class="row g-3">
            @forelse ($projects as $project)
                <div class="mt-3">
                    <h6>Ringkasan Tugas</h6>

                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-secondary">
                            Pending: {{ $project->pending_tasks_count }}
                        </span>

                        <span class="badge bg-primary">
                            Dalam Proses: {{ $project->in_progress_tasks_count }}
                        </span>

                        <span class="badge bg-success">
                            Selesai: {{ $project->completed_tasks_count }}
                        </span>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold">
                                {{ $project->name }}
                            </h5>

                            <p class="text-secondary mb-0">
                                {{ $project->description ?: 'Belum ada deskripsi.' }}
                            </p>

                            <div class="text-secondary small mt-3">
                                Dibuat {{ $project->created_at->format('d/m/Y') }}
                            </div>

                            <a href="{{ route('projects.show', $project) }}"
                                class="btn btn-outline-primary btn-sm mt-3">
                                Lihat Detail
                            </a>

                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus Proyek
                                </button>
                            </form>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 rounded-4">
                        <div class="card-body text-center py-5">
                            <h6 class="fw-bold">Belum ada proyek</h6>
                            <p class="text-secondary mb-0">
                                Buat proyek pertamamu menggunakan form di atas.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

</body>

</html>
