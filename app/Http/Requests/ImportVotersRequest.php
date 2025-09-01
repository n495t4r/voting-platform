<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportVotersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageVoters', $this->route('election'));
    }

    public function rules(): array
    {
        return [
            'voters_file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'voters_file.required' => 'Please select a CSV file to upload.',
            'voters_file.mimes' => 'The file must be a CSV file.',
            'voters_file.max' => 'The file size must not exceed 2MB.',
        ];
    }
}
