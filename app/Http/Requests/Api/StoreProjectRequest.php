<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'client_name'  => 'required|string|max:255',
            'client_email' => 'nullable|email',
            'total_amount' => 'nullable|numeric|min:0',
            'status'       => 'nullable|in:active,completed,cancelled',
            'started_at'   => 'nullable|date',
            'completed_at' => 'nullable|date|after_or_equal:started_at',
        ];
    }
}
