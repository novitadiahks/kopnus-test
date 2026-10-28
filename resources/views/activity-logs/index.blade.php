<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Aktivitas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Riwayat Aktivitas</h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="GET" action="{{ route('activity-logs.index') }}" class="card card-body mb-4">

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="action" class="form-label">Jenis aktivitas</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">Semua aktivitas</option>

                        @foreach ([
                                'PROJECT_CREATED' => 'Proyek dibuat',
                                'PROJECT_DELETED' => 'Proyek dihapus',
                                'TASK_CREATED' => 'Task dibuat',
                                'TASK_STATUS_UPDATED' => 'Status task diubah',
                            ] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['action'] ?? '') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="from" class="form-label">Dari tanggal</label>
                    <input type="date" name="from" id="from" class="form-control"
                        value="{{ $filters['from'] ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label for="to" class="form-label">Sampai tanggal</label>
                    <input type="date" name="to" id="to" class="form-control"
                        value="{{ $filters['to'] ?? '' }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">
                        Filter
                    </button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>Detail</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-nowrap">
                                    {{ $log->created_at?->format('d-m-Y H:i') }}
                                </td>

                                <td>
                                    {{ str_replace('_', ' ', $log->action) }}
                                </td>

                                <td>
                                    @if ($log->payload)
                                        @foreach ($log->payload as $key => $value)
                                            @if (is_scalar($value))
                                                <div>
                                                    <strong>
                                                        {{ str_replace('_', ' ', $key) }}:
                                                    </strong>
                                                    {{ $value }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    Belum ada aktivitas yang cocok dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-body">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</body>
</html>
