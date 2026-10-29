<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityLogFilterRequest;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(ActivityLogFilterRequest $request)
    {
        $filters = $request->validated();

        $logs = ActivityLog::query()
            ->where('user_id', $request->user()->id)
            ->when(
                $filters['action'] ?? null,
                fn ($query, $action) =>
                    $query->where('action', $action)
            )
            ->when(
                $filters['from'] ?? null,
                fn ($query, $from) =>
                    $query->whereDate('created_at', '>=', $from)
            )
            ->when(
                $filters['to'] ?? null,
                fn ($query, $to) =>
                    $query->whereDate('created_at', '<=', $to)
            )
            ->latest('created_at')
            ->latest('id')
            ->paginate(5)
            ->withQueryString();
    }
}
