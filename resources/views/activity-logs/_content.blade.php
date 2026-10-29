<div class="container-fluid px-0">

    <div class="d-flex flex-column flex-md-row justify-content-between
                align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2">Riwayat Aktivitas</h2>
            <p class="text-secondary mb-0">
                Pantau perubahan proyek dan task kamu dalam satu tempat.
            </p>
        </div>

        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis
                     px-3 py-2 align-self-start align-self-md-center">
            {{ $logs->total() }} aktivitas
        </span>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm auto-dismiss-alert">
            <strong>Filter tidak valid.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="rounded-3 bg-primary-subtle text-primary
                            d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px;">
                    <span class="fw-bold">☷</span>
                </div>

                <div>
                    <h6 class="fw-bold mb-0">Filter Aktivitas</h6>
                    <small class="text-secondary">
                        Cari aktivitas berdasarkan jenis dan tanggal.
                    </small>
                </div>
            </div>

            <form method="GET" action="{{ route('dashboard') }}">
                <input type="hidden" name="tab" value="activity">

                <div class="row g-3 align-items-end">
                    <div class="col-12 col-lg-4">
                        <label for="action" class="form-label fw-semibold small">
                            Jenis aktivitas
                        </label>

                        <select name="action" id="action" class="form-select">
                            <option value="">Semua aktivitas</option>

                            @foreach ([
                                'PROJECT_CREATED' => 'Proyek dibuat',
                                'PROJECT_DELETED' => 'Proyek dihapus',
                                'TASK_CREATED' => 'Task dibuat',
                                'TASK_STATUS_UPDATED' => 'Status task diubah',
                            ] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(($filters['action'] ?? '') === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="from" class="form-label fw-semibold small">
                            Dari tanggal
                        </label>

                        <input type="date" name="from" id="from"
                            class="form-control"
                            value="{{ $filters['from'] ?? '' }}">
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="to" class="form-label fw-semibold small">
                            Sampai tanggal
                        </label>

                        <input type="date" name="to" id="to"
                            class="form-control"
                            value="{{ $filters['to'] ?? '' }}">
                    </div>

                    <div class="col-12 col-lg-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Filter
                        </button>
                    </div>
                </div>

                @if (request()->filled('action') ||
                     request()->filled('from') ||
                     request()->filled('to'))
                    <div class="mt-3">
                        <a href="{{ route('dashboard', ['tab' => 'activity']) }}"
                           class="btn btn-sm btn-outline-secondary">
                            Reset filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 px-4 pt-4 pb-3">
            <h5 class="fw-bold mb-1">Daftar Aktivitas</h5>
            <p class="text-secondary small mb-0">
                Aktivitas terbaru ditampilkan terlebih dahulu.
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-secondary small text-nowrap">
                            WAKTU
                        </th>
                        <th class="py-3 text-secondary small">
                            AKTIVITAS
                        </th>
                        <th class="py-3 text-secondary small">
                            DETAIL
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            {{-- Waktu --}}
                            <td class="ps-4 py-3 text-nowrap">
                                <div class="fw-semibold small">
                                    {{ $log->created_at?->format('d M Y') ?? '-' }}
                                </div>
                                <div class="text-secondary small">
                                    {{ $log->created_at?->format('H:i') ?? '' }}
                                </div>
                            </td>

                            <td class="py-3">
                                @php
                                    $activityLabels = [
                                        'PROJECT_CREATED' => 'Proyek dibuat',
                                        'PROJECT_DELETED' => 'Proyek dihapus',
                                        'TASK_CREATED' => 'Task dibuat',
                                        'TASK_STATUS_UPDATED' => 'Status task diubah',
                                    ];

                                    $activityColors = [
                                        'PROJECT_CREATED' => 'success',
                                        'PROJECT_DELETED' => 'danger',
                                        'TASK_CREATED' => 'primary',
                                        'TASK_STATUS_UPDATED' => 'warning',
                                    ];
                                @endphp

                                <span class="badge rounded-pill
                                    bg-{{ $activityColors[$log->action] ?? 'secondary' }}-subtle
                                    text-{{ $activityColors[$log->action] ?? 'secondary' }}-emphasis
                                    px-3 py-2">
                                    {{ $activityLabels[$log->action] ?? str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>

                            <td class="py-3 pe-4">
                                @if ($log->payload)
                                    <div class="d-flex flex-column gap-1">
                                        @foreach ($log->payload as $key => $value)
                                            @if (is_scalar($value))
                                                <div class="small">
                                                    <span class="text-secondary">
                                                        {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                                    </span>
                                                    <span class="fw-medium text-break">
                                                        {{ $value }}
                                                    </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-secondary small">Tidak ada detail</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="mb-3">
                                    <span class="fs-1">📋</span>
                                </div>

                                <h6 class="fw-bold">Belum ada aktivitas</h6>

                                <p class="text-secondary small mb-0">
                                    Belum ada aktivitas yang cocok dengan filter kamu.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-3">
                <div class="d-flex flex-column flex-md-row
                            justify-content-between align-items-md-center gap-3">
                    <small class="text-secondary">
                        Menampilkan {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }}
                        dari {{ $logs->total() }} aktivitas
                    </small>

                    <div>
                        {{ $logs->appends(['tab' => 'activity'])->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>