<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'status' => [
                'nullable',
                'in:pending,in_progress,completed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Pencarian judul maksimal 255 karakter.',
            'status.in' => 'Status task tidak valid.',
        ];
    }
}