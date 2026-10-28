<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityLogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action' => [
                'nullable',
                'in:PROJECT_CREATED,PROJECT_DELETED,TASK_CREATED,TASK_STATUS_UPDATED',
            ],
            'from' => ['nullable', 'date'],
            'to' => [
                'nullable',
                'date',
                'after_or_equal:from',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'action.in' => 'Jenis aktivitas tidak valid.',
            'from.date' => 'Tanggal awal tidak valid.',
            'to.date' => 'Tanggal akhir tidak valid.',
            'to.after_or_equal' =>
                'Tanggal akhir tidak boleh sebelum tanggal awal.',
        ];
    }
}
