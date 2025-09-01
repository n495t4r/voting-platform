<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('election'));
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_select' => 'required|integer|min:1',
            'max_select' => 'required|integer|min:1|gte:min_select',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'max_select.gte' => 'Maximum selections must be greater than or equal to minimum selections.',
        ];
    }
}
