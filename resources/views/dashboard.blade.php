<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Proyek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold me-5" href="{{ url()->current() }}">
                Project Manager
            </a>

            <ul class="navbar-nav gap-2 me-auto">
                <li class="nav-item">
                    <a href="javascript:void(0)" id="projectsTab" onclick="showTab('projects')"
                        class="nav-link {{ request('tab') === 'activity' ? 'text-secondary' : 'active fw-semibold' }}">
                        Proyek Saya
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" id="activityTab" onclick="showTab('activity')"
                        class="nav-link {{ request('tab') === 'activity' ? 'active fw-semibold' : 'text-secondary' }}">
                        Riwayat Aktivitas
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center grow">
                <div class="d-flex align-items-center gap-3">
                    <span class="text-secondary small">
                        {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div id="projectsContent" class="{{ request('tab') === 'activity' ? 'd-none' : '' }}">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Proyek Saya</h2>
                    <p class="text-secondary mb-0">
                        Kelola semua proyekmu di sini.
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success auto-dismiss-alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger auto-dismiss-alert">
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

            <div class="row g-4">
                @forelse ($projects as $project)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            {{ $project->name }}
                                        </h5>

                                        <small class="text-secondary">
                                            Dibuat {{ $project->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>

                                    <span class="badge bg-light text-primary border rounded-pill">
                                        Proyek
                                    </span>
                                </div>
                                <p class="text-secondary mb-4">
                                    {{ $project->description ?: 'Belum ada deskripsi.' }}
                                </p>
                                <div class="bg-light rounded-3 p-3 mb-4">
                                    <h6 class="fw-semibold mb-3">
                                        Ringkasan Tugas
                                    </h6>

                                    <div class="d-flex flex-wrap gap-2">
                                        <span
                                            class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis px-3 py-2">
                                            Pending: {{ $project->pending_tasks_count }}
                                        </span>

                                        <span
                                            class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2">
                                            Proses: {{ $project->in_progress_tasks_count }}
                                        </span>

                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2">
                                            Selesai: {{ $project->completed_tasks_count }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('projects.show', $project) }}"
                                        class="btn btn-primary btn-sm flex-grow-1">
                                        Lihat Detail
                                    </a>

                                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body text-center py-5">
                                <div class="mb-3">
                                    <span class="fs-1">📁</span>
                                </div>

                                <h5 class="fw-bold">Belum ada proyek</h5>
                                <p class="text-secondary mb-0">
                                    Buat proyek pertamamu menggunakan form di atas.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div id="activityContent" class="{{ request('tab') === 'activity' ? '' : 'd-none' }}">
            @include('activity-logs._content', [
                'logs' => $logs,
                'filters' => $filters,
            ])
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.auto-dismiss-alert').delay(3000).fadeOut(500, function() {
                $(this).remove();
            });
        });

        function showTab(tab) {
            const isActivity = tab === 'activity';

            $('#projectsContent').toggleClass('d-none', isActivity);
            $('#activityContent').toggleClass('d-none', !isActivity);

            $('#projectsTab')
                .toggleClass('active', !isActivity)
                .toggleClass('text-secondary', isActivity);

            $('#activityTab')
                .toggleClass('active', isActivity)
                .toggleClass('text-secondary', !isActivity);

            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        }
    </script>
</body>

</html>
